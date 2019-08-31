<?php use util\config\Config; ?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta charset="utf-8"> <!-- utf-8 works for most cases -->

        <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->

        <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->

        <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->

        <title> <?= $asunto ?> </title> <!-- The title tag shows in email notifications, like Android 4.4. -->

        <style>
            html, body {
                height: 100%;
                width: 100%;
                font-family: 'Open Sans', sans-serif;
            }

            body {
                background: rgb(217, 216, 218);
                margin: 0;
                padding: 0;
            }

            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }

            .panel-default {
                border-color: #ddd;
            }

            .panel-body {
                padding: 15px;
            }

            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-justify {
                text-align: justify;
            }

            .table_view {
                height: 100%;
                margin: 0 auto;
                width: 100%;
            }

            .cell_view {
                margin: 0 auto;
                vertical-align: middle;
                width: 50%;
            }

            .cell_view.left {
                width: 35%;
            }

            .cell_view.right {
                width: 65%;
            }

            .margin_view {
                padding: 20px 30px;
            }

            .border_dotted {
                border-bottom: 1px dotted gray;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .separated {
                margin: 2.5% 0;
            }

            b, h2 {
                color: black;
            }

            .heading_view {
                background-color: rgb(225, 140, 54);
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                padding-top: 0;
                padding-bottom: 0;
            }

            .reference {
                padding: 0 10px;
            }

            .reference h3 {
                padding: 6px 0 7px;
                border: 1px solid #b0afb5;
                border-radius: 4px;
                background: #f8f9fa;
            }

            .instructions {
                background: #f8f9fa;
            }

            .instagram_button, .instagram_button:hover {
                background: #3f729b;
                color: white;
            }

            .social_media_images a  {
                text-decoration: none;
            }

            a, a:hover {
                color: #1155cc;
            }

            .btn {
                padding: 6px 12px;
                margin-bottom: 0;
                font-size: 14px;
                font-weight: 400;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                cursor: pointer;
                border: 1px solid transparent;
                border-radius: 4px;
                text-decoration: none;
            }

            .btn-primary, .btn-primary:hover {
                color: #fff;
                background-color: #337ab7;
                border-color: #2e6da4;
            }

            .btn-success, .btn-success:hover {
                color: #fff;
                background-color: #5cb85c;
                border-color: #4cae4c;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th,
            .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th,
            .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
                border: 1px solid #ddd;
            }

            .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
                background-color: #eee;
                opacity: 1;
            }

            textarea.form-control {
                height: auto;
            }

            .form-control {
                width: 100%;
                height: 34px;
                padding: 6px 12px;
                font-size: 14px;
                color: #555;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            }

            .responsive {
                width: 98%;
            }

            @media (min-width: 768px) {
                .responsive {
                    width: 84%;
                }
            }

            @media (min-width: 992px) {
                .responsive {
                    width: 66%;
                }
            }

            @media (min-width: 1200px) {
                .responsive {
                    width: 50%;
                }
            }
        </style>
    </head>
    <body>
        <div class = "responsive" style = "margin: 0 auto">
            <table width = "100%">
                <tr>
                    <td class = "cell_view">
                        <div class = "panel panel-default">
                            <div class = "margin_view heading_view">
                                <table class = "table_view">
                                    <tr>
                                        <td class = "cell_view text-center" style = "padding: 20px 0">
                                            <img src = "<?= $logo ?>" width = "20%" style = "margin: 0 auto" />
                                        </td>
                                    </tr>
                                </table>
                            </div>