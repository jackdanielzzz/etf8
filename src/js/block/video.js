$(() => {

    const toggleButton = document.querySelector('.js-toggle');
    const videoPlayer = document.getElementById('fullSizeVideo');
    const textBlock = document.getElementById('text');
    const videoOverlay = document.getElementById('video-overlay');

    const toggleVideo = (event) => {

        event.preventDefault();
        videoPlayer.controls = 'controls';
        videoPlayer.muted = !videoPlayer.muted;
        textBlock.style.display = 'none';
        videoOverlay.style.display = 'none';
        videoPlayer.volume = 0.9;
        videoPlayer.setAttribute("src", "../img/video.mp4");
        videoPlayer.play();
    };

    toggleButton.addEventListener('click', toggleVideo);

});