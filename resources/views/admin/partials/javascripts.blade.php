<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
<script src="{{ url('quickadmin/js') }}/bootstrap.min.js"></script>
<script src="{{ url('quickadmin/js') }}/main.js"></script>
<script src="{{ asset("js/jquery.multiselect.js") }}" type="text/javascript"></script>
{{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
<script src="https://johnny.github.io/jquery-sortable/js/jquery-sortable-min.js"></script>
<script src="//rubaxa.github.io/Sortable/Sortable.js"></script>
<script type="text/javascript" src="{{ url('quickadmin/js') }}/multi-select.js"></script>
<script type="text/javascript" src="{{ url('quickadmin/js') }}/drag-drop.js"></script>
<script type="text/javascript" src="{{ url('quickadmin/js') }}/ajaxForm.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    var data;
    var wrapper = $("#sortable"); //Fields wrapper
    $('.add_url').click(function (e) { //on add input button click
        e.preventDefault();
        data = $(".parentUrl .childUrl #inputUrl").val();
        var trim = data.trim();
        if (data !== '' && trim !== '') {
            var html = '<li class="ui-state-default custom_padding childLi ui-sortable-handle">' + data;
            html += '<input name="input_url[][0]" type="hidden" value=" ' + data + ' ">';
            html += '<a href="#" class="remove_allow">' +
                '<span class="glyphicon glyphicon-minus-sign"' +
                ' style="color:purple;' + 'font-size:30px;float:right" aria-hidden="true"></span></a>';
            html += '</li>';
            $(wrapper).append(html); //add input box
            data = $(".parentUrl .childUrl #inputUrl").val('');
        }
    });

    $(wrapper).on("click", ".remove_allow", function (e) {//user click on remove text
        e.preventDefault();
        $(this).parent().remove();
    });
</script>

<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            if( $('#sidebar').hasClass('active')){
            $('.page-content-wrapper .page-content').css('margin-left' ,'235px');
            }else{
                $('.page-content-wrapper .page-content').css('margin-left' ,'0px');
            }
            $('#sidebar').toggleClass('active');
            $(this).toggleClass('active');
        });
    });
    $('#epgFlush').click(function (e) {
        e.preventDefault();
        $("#flushForm").submit();
    });
    $('#changeUrlButton').click(function (e) {
        e.preventDefault();
        $("#changeUrl").submit();
    });
    $("#MyApiButton").on("change", function () {
        var myInput = $('#MyApiButton option:selected');
        $('#myApiValue').val(myInput.text());
    });

    $('#deleteServerLogs').click(function (e) {
        e.preventDefault();
        $("#deleteLogs").submit();
    });
    $("#myUser").on("change", function () {
        var myInput = $('#myUser option:selected');
        $('#myName').val(myInput.text());
    });

    $('#paginate').click(function (e) {
        e.preventDefault();
        $("#paginateForm").attr("action", "{{url("admin/paginate")}}");
        $("#paginateForm").submit();
    });

    $('#changeStatusStreamsButton').click(function (e) {
        e.preventDefault();
        $("#streamForm").attr("method", "post");
        $("#streamForm").attr("action", "{{url("admin/stream/change_status_multiStream")}}");
        $("#streamForm").submit();
    });

    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    });

    $('#deleteButton').click(function (e) {
        return confirm('Confirm Delete Operation !');
    });
    $('.deleteButton').click(function (e) {
        return confirm('Confirm Delete Operation !');
    });

    $('#deleteStreamsButton').click(function (e) {
        e.preventDefault();
        $("#streamForm").attr("action", "{{url("admin/stream/delete")}}");
        $("#streamForm").attr("onsubmit", "return confirm('Confirm Delete Operation !')");
        $("#streamForm").submit();
    });

    $('#forceUpdate').click(function () {
        if ($(this).is(":checked")) {
            $('.acceptableVersion').hide();

        } else {
            $('.acceptableVersion').show();
        }
    });
    $("#apkFile").on("change", function () {
        var myInput = $('#apkFile').val();
        var filename = myInput.substring(12);
        var n = filename.indexOf(".");
        var ext = filename.substring(n);
        var file = filename.replace(ext, "");

        $('#apkName').val(file);
    });

    $('#createServer').click(function (e) {
        e.preventDefault();
        $("#severForm").attr("action", "{{url("admin/server/save")}}");
        $("#severForm").submit();
    });
    $('#changeServerStreamsButton').click(function (e) {
        e.preventDefault();
        $("#streamForm").attr("method", "post");
        $("#streamForm").attr("action", "{{url("admin/stream/change_server_multiStream")}}");
        $("#streamForm").submit();
    });

    $('#restartLink').click(function (e) {
        e.preventDefault();
        $("#streamForm").submit();
    });
    $('#enableStreamsButton').click(function (e) {
        e.preventDefault();
        $("#streamForm").attr("action", "{{url("admin/streams/enable-all")}}");
        $("#streamForm").submit();
    });
    $('#disableStreamsButton').click(function (e) {
        e.preventDefault();
        $("#streamForm").attr("action", "{{url("admin/streams/disable-all")}}");
        $("#streamForm").submit();
    });

    $('#enableDevicesButton').click(function (e) {
        e.preventDefault();
        $("#status").val(1);
        $("#deviceForm").submit();
    });
    $('#disableDevicesButton').click(function (e) {
        e.preventDefault();
        $("#status").val(0);
        $("#deviceForm").submit();
    });

    $('.parent').click(function () {
        if ($(this).is(":checked")) {
            $('.child').each(function () {
                if ($(this).is(":checked") == false) {
                    $(this).click();
                }
            });
        } else {
            $('.child').each(function () {
                if ($(this).is(":checked") == true) {
                    $(this).click();
                }
            });
        }
    });
    $(function () {
        $("#sortable").sortable();
        $("#sortable").disableSelection();

    });
    $('.datepicker').datepicker({
        autoclose: true,
        dateFormat: "{{ config('quickadmin.date_format_jquery') }}"
    });

    $('.datetimepicker').datetimepicker({
        autoclose: true,
        dateFormat: "{{ config('quickadmin.date_format_jquery') }}",
        timeFormat: "{{ config('quickadmin.time_format_jquery') }}"
    });

    $('#datatable').dataTable({
        "language": {
            "url": "{{ trans('quickadmin::strings.datatable_url_language') }}"
        }
    });
    $('#serverIds').multiselect({
        columns: 4
    });
    $('#streamsIds').multiselect({
        columns: 4
    });

    $(document).ready(function () {
        getServerData()
        setInterval(getServerData, 5000);
        $('#apiId').on('change', function () {
            $("#acceptableVersion").empty()
            $.ajax({
                url: "{{url('admin/apk/versionCodes/')}}",
                data: {apiId: this.value},
                success: function (data) {
                    $(data).each(function () {
                        $("<option>").attr('value', this.versionCode).text(this.versionCode).appendTo('#acceptableVersion');
                    });
                }
            })
        })
    });

    function getServerData() {
        $('#servers-table').find('a').each(function () {
            $.ajax({
                url: "{{url('admin/server/info')}}",
                data: {url: this.href, id: this.id},
                success: function (data) {
                    console.log(data);
//                            $("#serverCpu" + data.name).html(data.cpu + "%")
                    $("#serverChannelsCount" + data.serverId).html("Streams : " + data.totalStreams)
                    if (data.cpu != undefined) {
                        $("#serverCpuText" + data.serverId).html(data.cpu + "%")
                        $("#serverCpuWidth" + data.serverId).css({
                            'width': data.cpu + "%",
                            "background-color": data.cpuBarColor
                        })
                    }
                    if (data.ram != undefined) {
                        $("#serverRamText" + data.serverId).html(data.ram + "%")
                        $("#serverRamWidth" + data.serverId).css({
                            'width': data.ram + "%",
                            "background-color": data.ramBarColor
                        })
                    }
                    $("#serverStoppedChannel" + data.serverId).html(data.stoppedStreams)
//                            var network = "<ul style='width: 100%;' class='dropdown-menu' aria-labelledby=dropdownMenu" + data.serverId + ">"
                    var network = ""
                    $(data.networkInfoResults).each(function (key, index) {
                        network += ("<li style='margin: 10px' class='text-grey' > Card name :" + index.networkName + "<br> Upload/download :" + index.networkUploadSpeed + "/" + index.downloadSpeed + "<hr></li>")
                        //

                    })
//                            network += "</ul>"
                    if (data.networkInfoResults != undefined) {
                        $("#networkCardNo" + data.serverId).html(data.networkInfoResults.length)
                        $("#networkCard" + data.serverId).html(network)

                    }
                    $("#serverOnlineChannels" + data.serverId).html(data.onlineStreams)
                    $("#serverOfflineChannels" + data.serverId).html(data.offlineStreams)
                    $("#serverOnlineUsers" + data.serverId).html(data.onlineUsersCount)

                },
                error: function () {
                }
            });

        });
    }
</script>


