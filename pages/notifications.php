<div class="author">Написал сайт - Илья Кутуков</div>

<div class="notifications_block_main">
</div>

<div class="notifications_block" id="ntfctn">
    <p>test</p>
    <div class="notifications_block_close" onclick="deleteNotification(this)">x</div>
</div>

<script type="text/javascript">
    function addNotification(message) {
        let notificationBlock = document.getElementById('ntfctn');
        let newNotification = notificationBlock.cloneNode(true);
        let notificationId = 'n' + new Date().getTime();
        newNotification.style.display = 'block';
        newNotification.id = notificationId;
        newNotification.childNodes[1].innerHTML = message;
        newNotification.childNodes[3].id = notificationId + '_close';

        (document.querySelector('.notifications_block_main')).appendChild(newNotification);
        deleteTimeOutNotification(newNotification);
    }

    function deleteNotification(e) {
        let notificationId = e.id.split('_');
        let notification = document.getElementById(notificationId[0]);
        notification.parentNode.removeChild(notification);
    }

    function deleteTimeOutNotification(notification) {
        setTimeout(() => {
            notification.parentNode.removeChild(notification);
        }, 20000);
    }
</script>