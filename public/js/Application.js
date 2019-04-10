$('pre code').each(function (i, block) {
    hljs.highlightBlock(block);
});


if (NodeList.prototype.forEach === undefined) {
    NodeList.prototype.forEach = function (callback) {
        [].forEach.call(this, callback)
    }
}

document.querySelectorAll('[data-ago]').forEach(function (node) {
    function setText () {
        var secondes = Math.round(new Date().getTime() / 1000 - parseInt(node.dataset.ago, 10))
        var prefix = secondes > 0 ? 'Released ' : 'In '
        var suffix = secondes > 0 ? ' ago.' : ''
        var wording = ""

        secondes = Math.abs(secondes);

        if (secondes < 60) {
            wording = secondes + ' second(s)'
        } else if (secondes < 3600) {
            wording = Math.round(secondes / 60) + ' minute(s)'
        } else if (secondes < 86400) {
            wording = Math.round(secondes / 3600) + ' hour(s)'
        } else if (secondes < 2592000) {
            wording = Math.round(secondes / 86400) + ' day(s)'
        } else if (secondes < 31104000) {
            wording = Math.round(secondes / 2592000) + ' mounth'
        } else if (secondes > 31104000) {
            wording = Math.round(secondes / 31104000) + ' year(s)'
        }



        node.innerHTML = prefix + wording + suffix

        window.setTimeout(function () {
            window.requestAnimationFrame(setText)
        }, 1000)
    }

    setText()
})