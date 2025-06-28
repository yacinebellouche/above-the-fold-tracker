window.addEventListener('load', () => {
    setTimeout(() => {
        const links = Array.from(document.querySelectorAll('a')).filter(link => {
            const rect = link.getBoundingClientRect();
            return rect.top >= 0 && rect.bottom <= window.innerHeight;
        }).map(link => link.href);

        const formData = new URLSearchParams();
        formData.append('action', 'above_fold_track');
        formData.append('nonce', AboveFoldTracker.nonce);
        formData.append('screen_width', window.innerWidth);
        formData.append('screen_height', window.innerHeight);

        links.forEach(link => {
            formData.append('links[]', link);
        });

        fetch(AboveFoldTracker.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        });
    }, 500);
});
