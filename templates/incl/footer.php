<?php
    use repository\GroupPermissionUser;
    use plataforma\SysConstants;
    use util\roles\RolesPolicy;
?>
            <!-- footer -->
            <footer class="footer">
                • Universidad Autónoma de Campeche © • <?= date("Y") ?>
            </footer>
            <!-- End footer -->
        </div>
<!-- End Page wrapper  -->
    </div>
<!-- End Wrapper -->

        <div id = "notify_body" style = "display: none" >
            <div data-notify = "container" class = "col-10 col-sm-3 col-lg-3 alert alert-{0} text-left" role="alert" style=" box-shadow: 2px 4px 15px rgba(0, 0, 1, 0.1);padding: 8px 8px">
                <div style = "display: table; width: 100%">
                    <div>
                                    <span data-notify="title">
                                        <font style = "font-size:14px;font-family:sans-serif;font-weight:600;margin-top: 0;text-shadow: -1px 1px 4px #ffff;">{1}</font>
                                    </span>
                        <span style="display:block;font-size:13px;text-align:unset;line-height: normal; text-shadow: -1px 1px 4px #ffff;width: 100%"  data-notify="message">{2}</span>
                    </div>
                    <div class = "close_notify_button" style = "cursor: pointer; display: table-cell; vertical-align: text-top; text-align: right; width: 10px">
                        <i class = "fa fa-times"></i>
                    </div>
                </div>
            </div>
        </div>

<!-- message  -->
<div style="position: fixed; bottom: 5px !important; right: 5px; display: none" id="content-noty">
    <div class="toast fade show toast-danger" id="connectionMessage" role="alert" aria-live="assertive"
         aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto"><i class="fas fa-wifi"></i> Conexión a internet </strong>
        </div>
        <div class="toast-body text-white" id="messageStatus">
            <i class="fas fa-circle-notch fa-spin"></i> Se perdio su conexión a internet..
        </div>
    </div>
</div>
<!--end message-->
    <script type = "text/javascript" src = "/assets/lightbox/js/lightbox.js"></script>

    <script nonce = "<?= $nonce_hash ?>">

        $(document).ready(function() {
            <?php
              if ( !empty($_SESSION['success']) && !empty($_SESSION['message']) ) {
                if ( $_SESSION['success'] ) { ?>
                noty.show('<?=$_SESSION['success']?>', "<?= $_SESSION['message'] ?>");
                <?php
                }
            }
            unset($_SESSION['success']);
            unset($_SESSION['message']);
             ?>
            $(".select2").each(function( _, elemento ) {
                if ( $(elemento).find("option").length > 1 ) {
                    $(elemento).select2({
                        language: '<?= $_SESSION[SysConstants::SESS_PARAM_ISO_639_1] ?>'
                    });
                }
            });

        });
        </script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script type = "text/javascript" src="/assets/js/js-custom/jquery.slimscroll.js"></script>
        <!--Menu sidebar -->
        <script type = "text/javascript" src="/assets/js/js-custom/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script type = "text/javascript" src="/assets/js/js-custom/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <!--Custom JavaScript -->
        <script type = "text/javascript" src="/assets/js/js-custom/custom.min.js"></script>
    </body>
</html>
