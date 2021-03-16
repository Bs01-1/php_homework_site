<?php
    use Classes\Repositories\AdvertisementRepository;
use Classes\Services\AdvertisementService;

global $mysqli;

    $requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
    $type = str_replace('/', '', $requestUrl);

    $advertisementTitle = ($type === 'sale') ? 'Купить офис до 200 м2 в Екатеринбурге' :
        'Аренда помещений в Екатеринбурге';

    $advertisementRepository = new AdvertisementRepository($mysqli);
    $advertisementService = new AdvertisementService($advertisementRepository);
    $advertisementCount = $advertisementService->getCountAdvertisement($type);

?>
<div class="advertisement_block_main">
    <div class="advertisement_info_block">
        <div class="advertisement_info_title"><?=$advertisementTitle?></div>
        <div class="advertisement_info_number"><p>Предложений : </p><?=$advertisementCount?></div>
    </div>
</div>
<div class="advertisement_more_advertisements_block">
    <div class="advertisement_more_advertisements" onclick="paginator()">Добавить объявления!</div>
</div>
<script type="text/javascript">
    let page = 0;
    let URL = document.URL.split('/')[3];
    
    let ticking = false;
    let lastKnownScrollPosition = 0;

    window.addEventListener('scroll', function() {
        lastKnownScrollPosition = window.scrollY;

        if (!ticking) {
            window.requestAnimationFrame(async function() {
                await checkingScrollPosition(lastKnownScrollPosition);
                ticking = false;
            });

            ticking = true;
        }
    });

    async function checkingScrollPosition(lastKnownScrollPosition) {
        let windowRelativeBottom = document.documentElement.getBoundingClientRect().bottom;

        if (windowRelativeBottom - lastKnownScrollPosition < 0){
            return;
        }
        if (windowRelativeBottom - lastKnownScrollPosition < 500){
            await paginator()
        }
    }

    (async function () {
        await paginator();
    }());

    async function paginator() {
        let formData = new FormData();
        formData.append('page', page);
        formData.append('type', URL);

        let result = await fetch('/api/get_advertisement', {
            method: 'POST',
            body: formData
        });

        let resultText = await result.text();
        if (resultText.length <= 2) {
            let advertisementButton = document.querySelector('.advertisement_more_advertisements_block');
            advertisementButton.parentNode.removeChild(advertisementButton);
        }

        let $selector = document.querySelector('.advertisement_block_main');
        $selector.innerHTML += (resultText);
        page++;

        let about = document.querySelectorAll('.advertisement_content_about');
        for (let i = 0; i < about.length; i++){
            if (about[i].innerHTML.length >= 251){
                about[i].innerHTML = about[i].innerHTML.substr(0, 250) + '...';
            }
        }
    }

    async function setAdvertisementVote(params) {
        let param = params.split('.');

        let formData = new FormData();
        formData.append('user_id', param[0]);
        formData.append('advertisement_id', param[1]);
        formData.append('positive_vote', param[2]);

        let result = (await fetch('/api/set_vote', {
            method: 'POST',
            body: formData
        })).text();

        let resultArray =  (await result).split('.');
        if (resultArray[2] !== undefined) {
            let advertisementBlock = document.getElementById(param[1] + 'r_a');

            let arrowBlock = advertisementBlock.querySelector('.advertisement_arrow');
            arrowBlock.innerHTML = resultArray[2];

            let ratingBlock = advertisementBlock.querySelector('.advertisement_rating');
            ratingBlock.innerHTML = resultArray[1];
        }

        addNotification(resultArray[0]);
    }
</script>