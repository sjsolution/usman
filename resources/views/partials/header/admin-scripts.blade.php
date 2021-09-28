
<script src="{{asset('js/admin/jtable/jquery-1.6.4.min.js')}}"></script>
<script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('vendors/js/vendor.bundle.addons.js')}}"></script>
<script src="{{ asset('select2/js/select2.min.js') }}"></script>

<script src="{{asset('js/dashboard.js')}}"></script>


<script src="{{asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/admin/themes/redmond/jquery-ui-1.8.16.custom.css')}}">
<link rel="stylesheet" href="{{asset('js/admin/jtable/themes/lightcolor/orange/jtable.css')}}">

<script src="{{asset('js/admin/jquery-ui-1.8.16.custom.min.js')}}"></script>
<script src="{{asset('js/admin/jtable/jquery.jtable.js')}}"></script>
<script src="{{ asset('js/parsley.js')}}"></script>
<script src="{{ asset('js/file-upload.js')}}"></script>
<script src="{{ asset('js/toastDemo.js') }}"></script>
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>


<!-- Incldue Pusher Js Client via CDN -->
<script src="https://js.pusher.com/4.2/pusher.min.js"></script>
<!-- Alert whenever a new notification is pusher to our Pusher Channel -->
<script>

var webURL = "{{ config('app.url')}}";

//Remember to replace key and cluster with your credentials.
var pusher = new Pusher('cb6a7799da02b4725fc1', {
    cluster: 'ap2',
    encrypted: true
});
 
console.log(pusher);

//Also remember to change channel and event name if your's are different.
var channel = pusher.subscribe('notify'); 
channel.bind('notify-event', function(notification) {
    console.log(notification);
    document.getElementById('notify_user').innerHTML = tmpl('notify-render-list-tmpl',notification);

   
});

$(document).ready(function(){
    notificationRender();
});


function notificationRender()
{
    var notificationRenderRequest = fetchRequest('/admin/notification/order/request/render');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    notificationRenderRequest.setBody(formData);

    notificationRenderRequest.post().then(function (response) {

        if (response.status === 200) {
            response.json().then((errors) => {
                document.getElementById('notify_user').innerHTML = tmpl('notify-render-list-tmpl',errors);

                $('#notificationDropdown').on('click',function(){
                    notificationSeenStatus(1);
                });
            });  
        }
    });

}

function notificationSeenStatus(type){

    var notificationSeenRequest = fetchRequest('/admin/notification/seen/status');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    formData.append("type",type);

    notificationSeenRequest.setBody(formData);

    notificationSeenRequest.post().then(function (response) {

        if (response.status === 200) {
            response.json().then((errors) => {
               console.log('success');
            });  
        }
    });
}

function isClickedNotification(event,id){

    var notificationClickedRequest = fetchRequest('/admin/notification/is_clicked');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    formData.append("id",id);

    notificationClickedRequest.setBody(formData);

    notificationClickedRequest.post().then(function (response) {

        if (response.status === 200) {
            window.location.href = '/admin/booking/list'
        }
    });
}

</script>
<script type="text/x-tmpl" id="notify-render-list-tmpl">
{% if(o.list.length) { %}
    <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
        <i class="mdi mdi-bell-outline"></i>
        <span class="count-symbol bg-danger"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" style="height: 350px;overflow: auto;">
        <h6 class="p-3 mb-0">Notification</h6>
        <div class="dropdown-divider"></div>
        {% for(var i in o.list){
            var item=o.list[i];              
        %} 
            <a class="dropdown-item preview-item" onclick="isClickedNotification(event,{%=item.id%});" href="#" style="background:{% if(item.notification_status[0].admin_is_clicked){ %} lightgrey {% } %};">
                <div class="preview-thumbnail">
                <div class="preview-icon bg-success">
                    <i class="mdi mdi-calendar"></i>
                </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                <h6 class="preview-subject font-weight-normal mb-1"> {%=item.title%}</h6>
                <p class="text-gray ellipsis mb-0">{%=item.description%}</p> 
                </div>
            </a>
            <div class="dropdown-divider"></div>
            
        {% } %}
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.notifications') }}"><h6 class="p-3 mb-0 text-center">See all notifications</h6></a>
        
        </div>
    </li>
   
{% } else{ %}
    <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          <span class="count-symbol bg">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <h6 class="p-3 mb-0">No new Notification</h6>
          <div class="dropdown-divider"></div>
          
        
        </div>
    </li>

{% } %}

</script>
