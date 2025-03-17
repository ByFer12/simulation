document.addEventListener('DOMContentLoaded', function() {
    let startTime = localStorage.getItem('sessionStartTime');
    const sessionTimeDisplay = document.getElementById('session-time');
  
    if (!startTime) {
      startTime = new Date().getTime();
      localStorage.setItem('sessionStartTime', startTime);
    } else {
      startTime = parseInt(startTime, 10);
    }
  
    function updateSessionTime() {
      const currentTime = new Date().getTime();
      const elapsedTime = Math.floor((currentTime - startTime) / 1000);
  
      const hours = Math.floor(elapsedTime / 3600);
      const minutes = Math.floor((elapsedTime % 3600) / 60);
      const seconds = elapsedTime % 60;
  
      sessionTimeDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }
  
    setInterval(updateSessionTime, 1000);
  
    window.addEventListener('beforeunload', function() {
      localStorage.removeItem('sessionStartTime');
    });
  });
  