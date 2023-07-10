<div class="image-input image-input-outline w-125px h-125px" data-kt-image-input="true" style="background-image: url( {{defaultImageUrl()}} )">
    <div class="image-input-wrapper w-125px h-125px" style="background-image: url( {{$current_image?:defaultImageUrl()}} )"></div>
   
    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
        <i class="bi bi-pencil-fill fs-7"></i>
        <input type="file" name="{{$input_name}}" accept=".png, .jpg, .jpeg" />
        <input type="hidden" name="{{$input_name.'_remove'}}" />
    </label>

    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
		<i class="bi bi-x fs-2"></i>
	</span>
     @if(!empty($current_image))
     <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
	 	<i class="bi bi-x fs-2"></i>
 	</span>
     @endif
 </div>
<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
