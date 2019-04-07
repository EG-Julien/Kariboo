$('pre code').each(function (i, block) {
    hljs.highlightBlock(block);
});

var $notif = $('#notifications')
$notif.fadeIn()
$notif.removeClass("hidden")

setTimeout(function() {
    $notif.fadeOut()
}, 3000)

if (NodeList.prototype.forEach === undefined) {
    NodeList.prototype.forEach = function (callback) {
        [].forEach.call(this, callback)
    }
}

document.querySelectorAll('[data-ago]').forEach(function (node) {
    function setText () {
        var secondes = Math.round(new Date().getTime() / 1000 - parseInt(node.dataset.ago, 10))
        var prefix = secondes > 0 ? 'Il y a ' : 'Dans '
        var wording = ""

        secondes = Math.abs(secondes);

        if (secondes < 60) {
            wording = secondes + ' seconde(s)'
        } else if (secondes < 3600) {
            wording = Math.round(secondes / 60) + ' minute(s)'
        } else if (secondes < 86400) {
            wording = Math.round(secondes / 3600) + ' heure(s)'
        } else if (secondes < 2592000) {
            wording = Math.round(secondes / 86400) + ' jour(s)'
        } else if (secondes < 31104000) {
            wording = Math.round(secondes / 2592000) + ' mois'
        } else if (secondes > 31104000) {
            wording = Math.round(secondes / 31104000) + ' année(s)'
        }



        node.innerHTML = prefix + wording

        window.setTimeout(function () {
            window.requestAnimationFrame(setText)
        }, 1000)
    }

    setText()
})