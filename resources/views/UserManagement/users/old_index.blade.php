@extends('layouts.tasks.layout')
@section('title','Add User')
@push('styles')
    <link type="text/css" rel="stylesheet" href="{{asset('dashboard/plugins/jsgrid/jsgrid.min.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('dashboard/plugins/jsgrid/jsgrid-theme.min.css')}}"/>

    <style>
        .external-pager {
            margin: 10px 0;
        }

        .external-pager .jsgrid-pager-current-page {
            background: #c4e2ff;
            color: #fff;
        }
    </style>
@endpush
@section('content')
    <div id="externalPager" class="external-pager"></div>
    <input type="hidden" name="loadDataUrl" value="{{route('all.users')}}">
    <div id="jsGrid"></div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{asset('dashboard/plugins/jsgrid/jsgrid.min.js')}}"></script>
    <script>
        (function (systemInstance, $) {
            String.prototype.capitalize = function () {
                return this.charAt(0).toUpperCase() + this.slice(1);
            };
            /*
            * pagerContainer: "#externalPager",
                pagerFormat: "current page: {pageIndex} &nbsp;&nbsp; {first} {prev} {pages} {next} {last} &nbsp;&nbsp; total pages: {pageCount} total items: {itemCount}",
                pagePrevText: "<",
                pageNextText: ">",
                pageFirstText: "<<",
                pageLastText: ">>",
                pageNavigatorNextText: "&#8230;",
                pageNavigatorPrevText: "&#8230;",
                *
                *
            *   rowRenderer: function(item) {
                var user = item;
                var $photo = $("<div>").addClass("client-photo").append($("<img>").attr("src", user.picture.large));
                var $info = $("<div>").addClass("client-info")
                    .append($("<p>").append($("<strong>").text(user.name.first.capitalize() + " " + user.name.last.capitalize())))
                    .append($("<p>").text("Location: " + user.location.city.capitalize() + ", " + user.location.street))
                    .append($("<p>").text("Email: " + user.email))
                    .append($("<p>").text("Phone: " + user.phone))
                    .append($("<p>").text("Cell: " + user.cell));

                return $("<tr>").append($("<td>").append($photo).append($info));
            },
            *
            * items:  window.countries, valueField: "Id", textField: "Name"
            * */
            let selectedItems = [];
            $(document).on('click', '#btnImportUser', function () {
                showDetailsDialog('Add', {})
            });

            $("#jsGrid").jsGrid({
                height: "auto",
                width: "100%",
                filtering: true,
                editing: false,
                sorting: true,
                paging: true,
                autoload: true,
                selecting: true,
                pageLoading: true,
                pageSize: 10,
                pageButtonCount: 5,
                controller: {
                    loadData: function (filter) {
                        console.log(filter);
                        filter['startIndex'] = (filter.pageIndex - 1) * filter.pageSize;
                        let d = $.Deferred();
                        $.ajax({
                            type: 'GET',
                            url: $('[name="loadDataUrl"]').val(),
                            data: filter,
                            dataType: "json"
                        }).done(function (response) {
                            d.resolve({'data': response['data']['data'], 'itemsCount': response['itemsCount']});
                        });

                        return d.promise();
                    }
                },
                fields: [
                    {
                        name: "name",
                        type: "text",
                        headerTemplate: function () {
                            return 'Name'
                        },
                        width: 100
                    },
                    {
                        name: "email",
                        type: "text",
                        headerTemplate: function () {
                            return 'Email'
                        },
                        width: 90
                    },
                    {name: "JobTitle", type: "text", width: 150},
                    {name: "UserLocation", type: "text",},
                    {
                        type: "control",
                        modeSwitchButton: false,
                        editButton: false,
                        headerTemplate: function () {
                            return `Actions`;
                        },
                        itemTemplate: function (_, item) {
                            return `<div style="display: flex; flex-direction:row; justify-content: space-between; " >
                                <button class="ui-button ui-widget ui-corner-all">View</button>
                                </div>`;
                        },
                        align: "center",
                        width: 50
                    },
                ],
            });


            $("#detailsDialog").dialog({
                autoOpen: false,
                width: 400,
                close: function () {
                    $("#detailsForm").validate().resetForm();
                    $("#detailsForm").find(".error").removeClass("error");
                }
            });

            $("#sort").click(function () {
                let field = $("#sortingField").val();
                $("#jsGrid").jsGrid("sort", field);
            });

            $(".config-panel input[type=checkbox]").on("click", function () {
                var $cb = $(this);
                $("#jsGrid").jsGrid("option", $cb.attr("id"), $cb.is(":checked"));
            });

            $("#detailsForm").validate({
                rules: {
                    name: "required",
                    age: {required: true, range: [18, 150]},
                    address: {required: true, minlength: 10},
                    country: "required"
                },
                messages: {
                    name: "Please enter name",
                    age: "Please enter valid age",
                    address: "Please enter address (more than 10 chars)",
                    country: "Please select country"
                },
                submitHandler: function () {
                    formSubmitHandler();
                }
            });

            let formSubmitHandler = $.noop;

            let showDetailsDialog = function (dialogType, client) {
                $("#name").val(client.Name);
                $("#age").val(client.Age);
                $("#address").val(client.Address);
                $("#country").val(client.Country);
                $("#married").prop("checked", client.Married);

                formSubmitHandler = function () {
                    saveClient(client, dialogType === "Add");
                };

                $("#detailsDialog").dialog("option", "title", dialogType + " Client")
                    .dialog("open");
            };

            let saveClient = function (client, isNew) {
                $.extend(client, {
                    Name: $("#name").val(),
                    Age: parseInt($("#age").val(), 10),
                    Address: $("#address").val(),
                    Country: parseInt($("#country").val(), 10),
                    Married: $("#married").is(":checked")
                });

                $("#jsGrid").jsGrid(isNew ? "insertItem" : "updateItem", client);

                $("#detailsDialog").dialog("close");
            };
        })(window.sysApp = window.sysApp || {}, jQuery);
    </script>
@endpush
