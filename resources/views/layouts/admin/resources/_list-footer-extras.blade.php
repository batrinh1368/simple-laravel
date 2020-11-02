<!-- Delete Confirm - Helper. Show a modal box -->
<div id="modalConfirmModelDelete" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="modalConfirmModelDeleteLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-red color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalConfirmModelDeleteLabel">Alert <i
                        class="fa fa-question"></i>
                </h4>
            </div>
            <div class="modal-body">
                <p> {{__a('confirm_delete')}}<i class="fa fa-question"></i></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnModalConfirmModelDelete" data-form-id="">Confirm
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnModalCancelModelDelete">
                    Cancel
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id="modalConfirmMutipleDelete" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="modalConfirmMutipleDeleteLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-red color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalConfirmMutipleDeleteLabel">Bạn có chắc muốn xóa <i
                        class="fa fa-question"></i>
                </h4>
            </div>
            <div class="modal-body">
                <p> Bạn có chắc muốn xóa <span id="itemCount"></span> bản ghi đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnModalConfirmMutipleDelete" data-form-id="">Xác nhận
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnModalCancelMutipleDelete">
                    Hủy bỏ
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Page scripts -->
@push('footer-scripts')
    {{-- Initiate Confirm Delete --}}
    <script type="text/javascript">
        $(function () {
            var $mutipleDeleteForm = $('#formDeleteMutiple');
            var $modalConfirmMutipleDelete = $('#modalConfirmMutipleDelete');
            $(document).on('click', '.btnOpenerModalConfirmModelDelete', function (e) {
                e.preventDefault();
                var formId = $(this).attr('data-form-id');
                var btnConfirm = $('#modalConfirmModelDelete').find('#btnModalConfirmModelDelete');
                if (btnConfirm.length) {
                    btnConfirm.attr('data-form-id', formId);
                }
                $('#modalConfirmModelDelete').modal('show');
            });
            // Modal Button Confirm Delete
            $(document).on('click', '#btnModalConfirmModelDelete', function (e) {
                e.preventDefault();
                var formId = $(this).attr('data-form-id');
                var form = $(document).find('#' + formId);
                if (form.length) {
                    form.submit();
                }
                $('#modalConfirmModelDelete').modal('hide');
            });
            // Modal Button Cancel Delete
            $(document).on('click', '#modalConfirmModelDelete #btnModalCancelModelDelete', function (e) {
                e.preventDefault();
                var btnConfirm = $('#modalConfirmModelDelete').find('#btnModalConfirmModelDelete');
                if (btnConfirm.length) {
                    btnConfirm.attr('data-form-id', "");
                }
                $('#modalConfirmModelDelete').modal('hide');
            });

            $(document).on('click', '#btnDeleteMutiple', function (e) {
                e.preventDefault();
                var $items = $(".chkDelete:checked");
                if ($items.length > 0) {
                    $modalConfirmMutipleDelete.find('#itemCount').text($items.length);

                    $idList = $mutipleDeleteForm.find('#id-list');
                    $.each($items, function () {
                        $idList.append('<input name="ids[]" value="' + this.value + '" />');
                    });
                    $modalConfirmMutipleDelete.modal('show');
                }
            });
            // Modal Button Confirm Delete
            $(document).on('click', '#btnModalConfirmMutipleDelete', function (e) {
                e.preventDefault();

                $mutipleDeleteForm.submit();
                $modalConfirmMutipleDelete.modal('hide');
            });
            // Modal Button Cancel Delete
            $(document).on('click', '#modalConfirmMutipleDelete #btnModalCancelMutipleDelete', function (e) {
                e.preventDefault();
                $mutipleDeleteForm.find('#id-list').empty();
                $modalConfirmMutipleDelete.modal('hide');
            });
            //Enable check and uncheck all functionality
            $(".checkbox-toggle").click(function () {
                var clicks = $(this).data('clicks');
                if (clicks) {
                    //Uncheck all checkboxes
                    $(".list-records input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                } else {
                    //Check all checkboxes
                    $(".list-records input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });

        });
    </script>
@endpush
