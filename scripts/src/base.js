export default class Base {
    loadComponent(id, page) {
        return new Promise((resolve, reject) => {
            $(id).load(page, function (response, status, xhr) {
                if (status == "error") {
                    $(id).html('<p>Error loading page.</p>');
                    console.error("Error loading page: " + xhr.status + " " + xhr.statusText);
                    reject(xhr);
                } else {
                    resolve(response);
                }
            });
        });
    }

    removeComponent(id) {
        $(id).remove();
    }
}
