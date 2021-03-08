<div class="test">

</div>
<button onclick="test()">Кнопка</button>
<script type="text/javascript">
    let page = 0;

    async function test() {
        let formData = new FormData();
        formData.append('page', page);

        let result = await fetch('/api/get_advertisement', {
            method: 'POST',
            body: formData
        })

        let $selector = document.querySelector('.test');
        $selector.innerHTML += (await result.text());
        page++;
    }

    (async function () {
        await test();
    }());
</script>