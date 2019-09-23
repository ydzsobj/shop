//选择产品
$("#good_id").select2({
    language: {
        inputTooShort: function () {
            return "请输入单品名关键字";
        }
    },
    "allowClear": true,
    "placeholder": {"id": "", "text": "请选择"},
    ajax: {
        url: "/admin/search_goods",
        dataType: 'json',
        delay: 500,
        data: function (params) {
            return {
                keywords: params.term, // search term
                page: params.page,
            };
        },
        processResults: function (data, params) {

            console.log(data, params);
            params.page = params.page || 1;

            return {
                results: data.data,
                pagination: {
                    more: (params.page * 30) < data.count
                }
            };
        },
        cache: true
    },
    // placeholder: 'Search for a repository',
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection
});

function formatRepo (repo) {
    console.log(repo);
    if (repo.loading) {
        return repo.text;
    }

    var $container = $(
        "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + repo.main_image_url + "' /></div>" +
        // "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'></div>" +
        "</div>"
    );

    $container.find(".select2-result-repository__title").text(repo.name);
    // $container.find(".select2-result-repository__description").text(repo.description);
    // $container.find(".select2-result-repository__forks").append(repo.forks_count + " Forks");
    // $container.find(".select2-result-repository__stargazers").append(repo.stargazers_count + " Stars");
    // $container.find(".select2-result-repository__watchers").append(repo.watchers_count + " Watchers");

    return $container;
}
function formatRepoSelection (repo) {
    return repo.name;
}