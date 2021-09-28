@extends('app')
@section('content')
    <style>
        .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
            position: fixed;
            width: 70%;
            z-index: 99;
            height: 66px;
            padding-top: 60px;
            padding-bottom: 43px;
            background: #fff;
            margin-bottom: 58px !important;
        }
        .fixed_top_settings {
            background: white;
            height:60px;
            width: 70%;
            z-index: 999;
            position: fixed;
            padding-bottom: 10px;
            padding-top: 20px;
        }
        .card .card-body {
            padding: 0px 20px!important;
        }
        table#users-table {
            margin-top: 120px !important;
        }
    </style>
<div class="content-wrapper">
    <div class="page-header"></div>

    <div class="row">
        <div class="col-lg-12  grid-margin stretch-card">
            <div class="card">
                <div class="card-body table-responsive">
                    <div class=" add_button">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 ">
                                <div class="fixed_top_settings">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h3 class="page-title"> List Management   </h3>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                                        <a  class="btn btn-gradient-danger btn-md btn-fill "  href="{{ route('list.create') }}"> Add List Type </a>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table "  id="users-table">
                        <thead>
                            <tr>
                                <th>Id.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="category-list">
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    <i class="fa fa-refresh fa-spin fa fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </td>
                            <tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="technician_assign_model"></div>

@endsection
@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>

<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".fixed_top_settings").css('margin-top', '-4px');
        } else {
            $(".fixed_top_settings").css('margin-top', '0px');
        }
    });
function showListType(event,serviceProviderId)
{

    var listTypeRequest = fetchRequest('/admin/sp/list/type');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    formData.append("service_provider_id", serviceProviderId);

    listTypeRequest.setBody(formData);

    listTypeRequest.post().then(function (response) {

        if (response.status === 200) {
            response.json().then((errors) => {
                document.getElementById('technician_assign_model').innerHTML = tmpl('technician-assign-list-tmpl',errors);
                $('#technician_modal_form').modal('show');
                // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                //     var target = $(e.target).attr("href") // activated tab
                //     var cate = $(e.target).attr("data-category_id") // activated tab
                //     var urd = $(e.target).attr("data-user_id") // activated tab

                // });
            });

        }else if(response.status === 422){
            response.json().then((errors) => {
                console.log('Error');
            });
        }
    });

}

function ratingAssigned(event,categoryId)
{
    event.preventDefault();

    $('#technician_assignment-'+categoryId).parsley().validate();

    if ($('#technician_assignment-'+categoryId).parsley().isValid()){

        var form = document.getElementById('technician_assignment-'+categoryId);

        var rankingUpdateRequest = fetchRequest('/admin/sp/ranking/assignment');

        var formData = new FormData(form);

        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        rankingUpdateRequest.setBody(formData);

        rankingUpdateRequest.post().then(function (response) {

            if (response.status === 200) {
                response.json().then((errors) => {
                    $('#technician_modal_form').modal('toggle');
                });

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });

         return true;

    }else{
        return false;
    }
}


$(function() {


    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('service.provider.list') !!}',
        "columns": [
            {data: 'id', name: 'id'},
            {data: 'full_name_en', name: 'full_name_en'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
        ],
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "iDisplayLength": 50,
        // "bStateSave": true,
        // "fnStateSave": function (oSettings, oData) {
        //     localStorage.setItem('offersDataTables', JSON.stringify(oData));
        // },
        // "fnStateLoad": function (oSettings) {
        //     return JSON.parse(localStorage.getItem('offersDataTables'));
        // }
        initComplete: function () {

        }

    });


});

</script>

<script type="text/x-tmpl" id="technician-assign-list-tmpl">
    {% if(o.list.length) { %}
    <div class="modal fade" id="technician_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">{%=o.list.full_name_en%}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{%=o.list[0].full_name_en%}</h4>

                        <ul class="nav nav-tabs" role="tablist">
                            {% for(var i in o.list[0].category){
                                var item=o.list[0].category[i];
                            %}

                            <li class="nav-item">
                                <a class="nav-link show {% if(i==0){ %} active {% } %}" id="{%=item.categoryname.name_en%}-tab" data-toggle="tab" href="#{%=item.categoryname.name_en%}-1" role="tab" aria-controls="{%=item.categoryname.name_en%}" aria-selected="true" data-user_id="{%= o.list[0].id %}" data-category_id="{%= item.categoryname.id %}">{%=item.categoryname.name_en%}</a>
                            </li>

                            {% } %}

                        </ul>

                        <div class="tab-content">



                        {% for(var i in o.list[0].category){
                            var item=o.list[0].category[i];
                            console.log(item.categoryname.name_en);
                        %}

                        <div class="tab-pane fade {% if(i==0){ %} active show {% } %}" id="{%=item.categoryname.name_en%}-1" role="tabpanel" aria-labelledby="{%=item.categoryname.name_en%}-tab">
                            <div class="media">
                                <div class="media-body">
                                <form method="post" id="technician_assignment-{%=item.categoryname.id%}" onsubmit="return ratingAssigned(event,{%=item.categoryname.id%})">
                                    <input type="hidden" name="category_id" value="{%=item.categoryname.id%}">
                                    <input type="hidden" name="user_id" value="{%=o.list[0].id%}">
                                    {% for(var j in o.listType){

                                        var listItem=o.listType[j];


                                    %}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="exampleInputUsername2" class="col-form-label">{%=listItem.name_en%}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" name="list_type[{%=listItem.id%}]" value="{%=(item.categoryname.list_type.length > 0 && item.categoryname.list_type[j] != undefined) ? item.categoryname.list_type[j].rank  : 0 %}">
                                        </div>
                                    </div>

                                    {% } %}

                                    <input type="submit" class="btn btn-success" value="Update">
                                </form>

                                </div>

                            </div>


                        </div>

                        {% } %}

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>

            </div>

        </div>
    </div>


    {% } %}

</script>
@endsection
