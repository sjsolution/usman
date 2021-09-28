
@extends('app')
@section('content')
  <div class="page-header">
    <!-- <h3 class="page-title">Service Provider Details</h3>  -->
    <nav aria-label="breadcrumb">

    </nav>
  </div>
  <section class="content">
      <div class="card">
        <div class="card-body">
        <h3 class="page-title">Service Provider Details</h3> 
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">

              <table class="table table-striped">
              <tr>
                <th>Name</th>
                <td><?php  echo wordwrap($serviceprovider->full_name_en,30,"<br>\n", true); ?></td>
                <th>الاسم</th>
                <td><?php  echo wordwrap($serviceprovider->full_name_ar,30,"<br>\n", true); ?></td>
              </tr>

              <tr>
                <th>Incharge's Name</th>
                <td><?php  echo wordwrap($serviceprovider->person_incharge,30,"<br>\n", true); ?></td>

                <th>Incharge's mobile no</th>
                <td><?php  echo wordwrap($serviceprovider->country_code.' '.$serviceprovider->mobile_number,30,"<br>\n", true); ?></td>

              </tr>

              <tr>
                <th>Phone Number</th>
                <td><?php  echo wordwrap($serviceprovider->phone_number,30,"<br>\n", true); ?> </td>
                  <th>Address</th>
                  <td><?php  echo wordwrap($serviceprovider->address,30,"<br>\n", true); ?></td>
              </tr>
             <tr>
                    <th>Bank's Name </th>
                    <td><?php  echo wordwrap($serviceprovider->bank_name,30,"<br>\n", true); ?></td>
                    <th>IBAN </th>
                    <td><?php  echo wordwrap($serviceprovider->iban,30,"<br>\n", true); ?></td>
             </tr>

             <tr>
                <th>Supplier Code</th>
                <td>{{!empty($serviceprovider->supplier_code)?$serviceprovider->supplier_code:"--"}}</td>
             </tr>

             <tr>
                    <th>Fixed Price </th>
                    <td><?php  echo wordwrap($serviceprovider->fixed_price.' KWD',30,"<br>\n", true); ?> </td>
                     <th>Percentage (In %) </th>
                     <td><?php  echo wordwrap($serviceprovider->maak_percentage.'%',30,"<br>\n", true); ?></td>

             </tr>


              <tr>
                     <th>Monthly Fees</th>
                     <td><?php  echo wordwrap($serviceprovider->monthly_fees.'',30,"<br>\n", true); ?></td>
                      <th>Setup Fees</th>
                      <td><?php  echo wordwrap($serviceprovider->setup_fee.' KWD',30,"<br>\n", true); ?></td>
              </tr>
              <tr>
                     <th>Categories </th>
                     <td>
                       <?php
                       if(!empty($category)){
                         foreach ($category as $categorydata){
                          if(!empty($categorydata['categoryname']['name_en']) && $categorydata['categoryname']['name_en'] !=null){
                          echo  $categorydata['categoryname']['name_en']."<br>";
                        }
                      }
                    }
              ?>

                     </td>
                      <td>
                        <?php
                        if(!empty($category)){
                          foreach ($category as $categorydataar){
                           if(!empty($categorydataar['categoryname']['name_ar']) && $categorydataar['categoryname']['name_en'] !=null){
                           echo  $categorydataar['categoryname']['name_ar']."<br>";
                         }
                       }
                     }
                        ?>
                      </td>
                      <th>الاقسام
                      </th>

              </tr>

              <tr>
                     <th>Sub Categories </th>
                     <td>
                       <?php
                       if(!empty($subcategory)){
                         foreach ($subcategory as $subcategorydata){
                          if(!empty($subcategorydata['categoryname']['name_en']) && $subcategorydata['categoryname']['name_en'] !=null){
                          echo  $subcategorydata['categoryname']['name_en']."<br>";
                        }
                      }
                    }
                       ?>

                     </td>
                      <td>
                        <?php
                        if(!empty($subcategory)){
                          foreach ($subcategory as $subcategorydataar){
                           if(!empty($subcategorydataar['categoryname']['name_ar']) && $subcategorydataar['categoryname']['name_en'] !=null){
                           echo  $subcategorydataar['categoryname']['name_ar']."<br>";
                         }
                       }
                     }
                        ?>
                      </td>
                      <th>
                        الفئات الفرعية
                      </th>

              </tr>
            </table>
            <table class="table table-striped">
              @if(!empty($serviceprovider->cityareas) && $serviceprovider->cityareas !=null)
                @foreach ($serviceprovider->cityareas as $key => $spcountiesdata)
                  <tr>
                  <th>Country
                  </th>
                  <td>
                    @foreach ($country as $countries)
                      @if($countries['id'] == $spcountiesdata['country_id'])
                        {{$countries['name']}}
                      @endif
                    @endforeach
                  </td>
                  <th>City
                  </th>
                  <td>
                    @foreach ($city as $cities)

                      @if($cities['id'] ==$spcountiesdata['city_id'])
                        {{$cities['name_en']}}
                      @endif
                    @endforeach
                  </td>
                  <th>Area
                  </th>
                  <td>
                    <?php
                    $areaIds=[];
                    foreach ($spcountiesdata['coutrycitywisearea'] as $aresDatas) {
                      $areaIds[]=$aresDatas['area_id'];
                    }
                    $arestring =[];
                    foreach ($area as $keyarea=>$areas) {
                        if(in_array($areas['id'],$areaIds)){
                          $arestring[]= $areas['name_en'];
                      }
                    }
                    $areaNames='';
                    if(!empty($arestring)){
                      $areaNames = implode(',',$arestring);
                      echo wordwrap($areaNames,30,"<br>\n", true);
                    }
                  ?>
                  </td>
                  </tr>
                @endforeach
              @endif
            </table>
            <br>
              <input type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">
            </div>
            <!-- /.box-body -->
          </div>
          </div>
          </div>
                    <!-- /.box -->
</div>
</section>

<script>
$(document).ready(function(){
  if ($("#lightgallery").length) {
    $("#lightgallery").lightGallery();
  }
  if ($("#lightgallery-without-thumb").length) {
    $("#lightgallery-without-thumb").lightGallery({
      thumbnail: true,
      animateThumb: false,
      showThumbByDefault: false
    });
  }
  if ($("#video-gallery").length) {
    $("#video-gallery").lightGallery();
  }
});
</script>
@endsection
