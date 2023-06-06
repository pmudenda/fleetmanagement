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
    return $('<option value="' + project['code_project'] + '">' + project['description'] + '</option>');
    /*if (project.loading) {
        return project.text;
    }

    let $container = $(
        `<div class='select2-result-repository clearfix'>
                     <div class='select2-result-repository__meta'>
                        <div class='select2-result-repository__title'></div>
                        <div class='select2-result-repository__description'></div>
                    </div>
                </div>`
    );

    $container.find(".select2-result-repository__title").text(project.code_project);
    $container.find(".select2-result-repository__description").text(project.description);

    return $container;*/
}

function formatRepoSelection(project) {
    if (!project['code_project']) {
        return project['description'];
    }
    $('[name="projectCode"]').val(project['code_project']);
    return project.project_code + ":" + project.description;
}

function formatResults(items) {

    return $.map(items, function (obj) {
        return {
            "id": obj['code_project'],
            "text": obj.description
        };
    });

}

$('[name="project_code"]').on('change', function () {
    $('.project-code-ajax').select2('close');
});
