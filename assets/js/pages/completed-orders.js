(function() {
    controlPageUtils();

    const formDate = document.getElementById('form-date');
    const status = document.getElementById('fireads-purchases-status');
    const dateFrom = document.getElementById('fireads-purchases-date-from');
    const dateTo = document.getElementById('fireads-purchases-date-to');

    flatpickr(dateFrom, {
        dateFormat: "Y-m-d"
    });

    flatpickr(dateTo, {
        dateFormat: "Y-m-d"
    });

    formDate.onsubmit = (e) => {
        e.preventDefault();
        const params = new URLSearchParams(location.search);

        if (status.value) {
            params.set('status', status.value);
        } else {
            params.has('status') && params.delete('status');
        }

        if (dateFrom.value) {
            params.set('date-from', (new Date(dateFrom.value).getTime() / 1000).toString());
        } else {
            params.has('date-from') && params.delete('date-from');
        }

        if (dateTo.value) {
            params.set('date-to', (new Date(dateTo.value).getTime() / 1000).toString());
        } else {
            params.has('date-to') && params.delete('date-to');
        }

        const newUrl = location.origin + location.pathname + '?' + params.toString();
        if (location.href !== newUrl) {
            location.href = newUrl;
        }
    };

    function controlPageUtils() {
        const btnPageFirst = document.getElementById('btn-page-first');
        const btnPagePrevious = document.getElementById('btn-page-previous');
        const btnPageNext = document.getElementById('btn-page-next');
        const btnPageLast = document.getElementById('btn-page-last');
        const currentPageInput = document.getElementById('current-page-input');
        const currentPageNumber = parseInt(currentPageInput.value);
        const pagesCount = parseInt(document.getElementById('pages-count').innerText);
        const params = new URLSearchParams(location.search);

        currentPageInput.onkeydown = (e) => {
            if (e.key === 'Enter') {
                params.set('page-number', currentPageInput.value);
                redirectToNewPage();
            }
        };

        if (btnPageFirst) {
            btnPageFirst.onclick = () => {
                params.set('page-number', '1');
                redirectToNewPage();
            };

            btnPagePrevious.onclick = () => {
                params.set('page-number', (currentPageNumber - 1).toString());
                redirectToNewPage();
            };
        }

        if (btnPageNext) {
            btnPageNext.onclick = () => {
                params.set('page-number', (currentPageNumber + 1).toString());
                redirectToNewPage();
            };

            btnPageLast.onclick = () => {
                params.set('page-number', pagesCount.toString());
                redirectToNewPage();
            };
        }

        function redirectToNewPage() {
            location.href = location.origin + location.pathname + '?' + params.toString();
        }
    }
})();
