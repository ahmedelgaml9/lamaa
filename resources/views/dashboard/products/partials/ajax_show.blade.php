<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{$product->title}}</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>
<div class="modal-body">
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.title')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->title}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.price')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->price}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.sku')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->sku}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.cities')}}</label>
        <div class="col-lg-8">
            @foreach($product->cities as $city)
                <span class="fw-bolder fs-6 text-gray-800"><span class="badge badge-info">{{$city->name}}</span></span>
            @endforeach
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.available_quantity')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->quantity}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.critical_quantity')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->critical_quantity > 0?:'غير محدد'}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.description')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{$product->description}}</span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">{{__('admin.status')}}</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800"><span class="badge badge-light-{{intStatusClassLabels($product->available)}}">{{__('admin.int_status_options.'.(int)$product->available)}}</span></span>
        </div>
    </div>
</div>

