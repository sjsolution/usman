<select name="parent_id" id="parent_id" class="form-control" required>
@foreach ($category as $categories)
<option value="{{$categories->id}}">{{$categories->name_en}}</option>
@endforeach
</select>
