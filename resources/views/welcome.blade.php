<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>vidComp</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>

    </style>
    <title></title>
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1021/styles/kendo.common-material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1021/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1021/styles/kendo.material.mobile.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://kendo.cdn.telerik.com/2020.3.1021/js/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

    <script src="https://kendo.cdn.telerik.com/2020.3.1021/js/kendo.all.min.js"></script>

    <style type="text/css">
        body {
            font-family: 'Nunito';
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class='row'>
        <div class='col m-5'>
            <form action="/encodeVideo" method="POST" enctype="multipart/form-data">
                @csrf
                <div class='row'>
                    <div class='col-2'>
                        <div class='row'>
                            <div class='col'>
                                <h4>Transcoder</h4>
                                <br>

                                <input type="radio" name="transcoder" value="PB"
                                    data-bind="checked: radioValue" />PBMedia
                                <br>
                                <input type="radio" name="transcoder" value="FF"
                                    data-bind="checked: radioValue" />FFMpeg Native
                                <br>
                                <input type="radio" name="transcoder" value="HB" checked
                                    data-bind="checked: radioValue" />HandBrakeCLI
                            </div>
                        </div>
                    </div>

                    <div class='col-2'>
                        <div class='row'>
                            <div class='col'>
                                <h4>Resolution</h4>
                                <br>

                                <select id="resolution" name='resolution' style="width: 100%;">
                                    <option value='256x144'>256x144</option>
                                    <option value='426x240'>426x240</option>
                                    <option value='640x360'>640x360</option>
                                    <option value='854x480'>854x480</option>
                                    <option value='1280x720'>1280x720</option>
                                    <option value='1920x1080'>1920x1080</option>
                                    <option value='2560x1440'>2560x1440</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class='col-2'>
                        <div class='row'>
                            <div class='col'>
                                <h4>File</h4>
                                <br>

                                <input name="video_file" id="file" type="file" aria-label="file" />

                            </div>
                        </div>
                    </div>

                    <div class='col-2'>
                        <div class='row'>
                            <div class='col'>
                                <h4>Bitrate</h4>
                                <br>

                                <select id="bitrate" name='bitrate' style="width: 100%;">
                                    <option value='128'>128 kbps</option>
                                    <option value='256'>256 kbps</option>
                                    <option value='512'>512 kbps</option>
                                    <option value='1024'>1024 kbps</option>
                                    <option value='2048'>2048 kbps</option>
                                    <option value='4096'>4096 kbps</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class='col-2'>
                        <button type="submit" class="k-button k-primary">Transcode</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class='row'>
        <div class='col m-5'>
            <div id="grid"></div>
        </div>
    </div>

</body>

<script>
    $(document).ready(function () {
        $("#resolution").kendoDropDownList();
        $("#bitrate").kendoDropDownList();

        $("#file").kendoUpload();

        $("#grid").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: '/fetch',
                        type: "GET",
                        dataType: "json"
                    }
                },
            },
            height: 550,
            groupable: true,
            sortable: true,
            pageable: false,
            columns: [{
                field: "original_file_name",
                title: "Original Name"
            }, {
                field: "original_file_size",
                title: "Original size"
            }, {
                field: "created_at",
                title: "Started on"
            }, {
                field: "compressed_file_name",
                title: "Compressed Name"
            }, {
                field: "compressed_file_size",
                title: "Compressed size"
            }, {
                field: "completed_at",
                title: "Completed on"
            }, {
                field: "transcoder",
                title: "Transcoder"
            }, {
                field: "resolution",
                title: "Resolution"
            }, {
                field: "bitrate",
                title: "Bitrate"
            }]
        });
    });
</script>

</html>

