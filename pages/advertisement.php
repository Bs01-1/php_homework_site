<div class="advertisement_block_main">

</div>
<button onclick="paginator()">Кнопка</button>
<script type="text/javascript">
    let page = 0;
    let URL = document.URL.split('/')[3];

    async function paginator() {
        let formData = new FormData();
        formData.append('page', page);
        formData.append('type', URL);

        let result = await fetch('/api/get_advertisement', {
            method: 'POST',
            body: formData
        });

        let $selector = document.querySelector('.advertisement_block_main');
        $selector.innerHTML += (await result.text());
        page++;

        let titles = document.querySelectorAll('.advertisement_content_title');
        for (let i = 0; i < titles.length; i++){
            if (titles[i].children[0].innerHTML.length >= 45){
                let inner_html = titles[i].children[0].innerHTML.substr(0, 45) + '...'
                titles[i].children[0].innerHTML = inner_html;
            }
        }
    }

    (async function () {
        await paginator();
    }());
</script>