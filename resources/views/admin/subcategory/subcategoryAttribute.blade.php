                <div class="col-md-6 attribute_en">

                  <div class="form-group">
                    <?php $attributesEn = json_decode($subcategory->attributes_en) ?>


                    @foreach ($attributesEn as $attribute)

                    {{$attribute}}

                      <button type="button" class="btn btn-sm btn-danger btn-gradient deleteattributes" data-id="{{$subcategory->id}}" data-id1="{{$attribute}}" data-id2="en">X</button>
                  <br>  <br>

                    @endforeach
                  </div>
                </div>




                <div class="col-md-6 attribute_ar">

                  <div class="form-group" style="text-align:right;">
                    <?php  $attributesAr = json_decode($subcategory->attributes_ar); ?>
                    @foreach ($attributesAr as $key=>$attribut)
                    {{$attribut}}
                      <button type="button" class="btn btn-sm btn-gradient-danger deleteattributes" data-id="{{$subcategory->id}}" data-id1="{{$attribut}}" data-id2="ar">X</button>
                    <br>
                      <br>
                    @endforeach
                  </div>

                </div>
