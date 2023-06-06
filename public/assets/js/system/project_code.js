'use strict';
$(document).ready(function () {

});

function initProjectSelector(selector) {

    $(selector).select2({
        selectOnClose: true,
        multiple: false,
        quietMillis: 100,
        id: function (project) {
            return project.code_project;
        },
        theme: 'bootstrap4',
        ajax: {
            delay: 250,
            beforeSend: function () {
                window.showLoaderModal(false);
                window.loaderVisible = false;
            },
            url: document.querySelector('#projects_url').value,
            dataType: 'json',
            data: function (params) {
                return {
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: formatResults(data.items),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'Enter Project name | Code',
        minimumInputLength: 4,
        templateResult: formatRepo,
        //formatRepo,
        templateSelection: formatRepoSelection
    });
}


function formatRepo(project) {
    if (project.loading)
        return project.text;
    return $('<option value="' + project['id'] + '">' + project['text'] + '</option>');
}

/**
 *
 * @param project {id: iValue, text:labelVal}
 * @returns {*|string}
 */
function formatRepoSelection(project) {
    if (!project['id']) {
        return project['text'];
    }
    $('[name="projectCode"]').val(project['id']);
    return project['id'] + ":" + project['text'];
}

/**
 * prepares results
 * @param items
 * @returns {*}
 */
function formatResults(items) {

    return $.map(items, function (obj) {
        return {
            "id": obj['code_project'],
            "text": obj['code_project'] + ':' + obj.description
        };
    });

}
