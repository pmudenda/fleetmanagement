'use strict';
$(document).ready(function () {
    $('.project-code-ajax').select2({
        theme: 'bootstrap4',
        ajax: {
            delay: 250,
            beforeSend: function(){
                //window.showLoaderModal(false);
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
        placeholder: 'Enter Project Code | Description',
        minimumInputLength: 4,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    function formatRepo(repo) {
        if (repo.loading) {
            return repo.text;
        }
        console.log(repo);

        let $container = $(
            `<div class='select2-result-repository clearfix'>
                         <div class='select2-result-repository__meta'>
                            <div class='select2-result-repository__title'></div>
                            <div class='select2-result-repository__description'></div>
                        </div>
                    </div>`
        );

        $container.find(".select2-result-repository__title").text(repo.project_code);
        $container.find(".select2-result-repository__description").text(repo.description);

        return $container;
    }

    function formatRepoSelection(repo) {
        if(!repo['id']){
            return repo['text'];
        }
        return repo.project_code + ":" + repo.description;
    }

    function formatResults(items) {
        return $.map(items, function (obj) {
            obj.id = obj.id || obj.project_code; // replace pk with your identifier
            obj.text = obj.text || obj.description;
            return obj;
        });
    }
});

$('.project-code-ajax').on('change', function () {
    $('.project-code-ajax').select2('close');
});
