(($) => {
        const searchForm = document.createElement('form');
        searchForm.action = 'http://localhost/wpbasetheme/';
        searchForm.method = 'post';
        // Search query input
        const searchQueryInput = document.createElement('input');
        searchQueryInput.type = 'hidden';
        searchQueryInput.name = 's';
        searchQueryInput.value = TASearchQuery.s ?? '';
        searchForm.appendChild(searchQueryInput);

        // Page number input
        const pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = TASearchQuery.page ?? 1;
        searchForm.appendChild(pageInput);

        // Post type input
        const postTypeInput = document.createElement('input');
        postTypeInput.type = 'hidden';
        postTypeInput.name = 'post_type';
        postTypeInput.value = 'ta_article';
        searchForm.appendChild(postTypeInput);

        document.body.appendChild(searchForm);

        /**
        *   When a pagination button is clicked, submit the form with the correct
        *   page number.
        */
        $(document).on('click', '.pagination-articles .page-numbers:not(.dots):not(.current)', function(event) {
            let pageNumber = 1;
            event.preventDefault();

            if( $(this).hasClass('prev') )
                pageNumber = parseInt($('.pagination-articles .page-numbers.current').text()) - 1;
            else if( $(this).hasClass('next') )
                pageNumber = parseInt($('.pagination-articles .page-numbers.current').text()) + 1;
            else
                pageNumber = parseInt($(this).text());

            pageInput.value = pageNumber;
            searchForm.submit();
        })
})(jQuery);
