<div class="modal fade" id="kt_import_customers_modal" tabindex="-1" aria-labelledby="kt_import_customers_modal_label" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kt_import_customers_modal_label">{{__('إستيراد مجموعة مستخدمين')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Wrapper-->
                    <div>
                        <form method="POST" action="{{route('customers.importExcel')}}"  enctype="multipart/form-data">
                            @csrf
                            <div class="mb-10">
                                <label class="form-label fw-bold" for="import_orders_btn">{{__('اختر الملف (بصيغة اكسيل)')}}:</label>
                                <div>
                                    <input id="import_orders_btn" class="form-control" type="file" name="imported_file">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('trans_admin.import')}}</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>
