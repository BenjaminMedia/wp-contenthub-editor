function FocalPoint(image, input) {
  var imageW, imageH, imgPos, crosshair;
  var showCrosshair = true;

  if (!input.value) {
    input.value = '0.5,0.5';
  }

  this.destroy = function() {
    crosshair.parentNode.removeChild(crosshair);
    image.removeEventListener('click', registerFocalPoint);
  }

  function findPosition(oElement) {
    if (typeof(oElement.offsetParent) != "undefined") {
      for (var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
        posX += oElement.offsetLeft;
        posY += oElement.offsetTop;
      }
      return {x: posX, y: posY};
    }
    else {
      return {x: oElement.x, y: oElement.y};
    }
  }

  function registerFocalPoint(e) {
    if (!showCrosshair) {
      return;
    }
    var posX = 0;
    var posY = 0;
    if (!e) var e = window.event;
    if (e.pageX || e.pageY) {
      posX = e.pageX;
      posY = e.pageY;
    } else if (e.clientX || e.clientY) {
      posX = e.clientX + document.body.scrollLeft
        + document.documentElement.scrollLeft;
      posY = e.clientY + document.body.scrollTop
        + document.documentElement.scrollTop;
    }

    posX = posX - imgPos.x;
    posY = posY - imgPos.y;

    var relX = Math.round((posX / imageW) * 100) / 100;
    var relY = Math.round((posY / imageH) * 100) / 100;

    input.value = relX + ',' + relY;

    displayCrosshair();

  }

  function displayCrosshair() {
    var relPos = input.value.split(',');
    var posX = relPos[0] * imageW;
    var posY = relPos[1] * imageH;

    crosshair.style.left = (posX - 64) + 'px';
    crosshair.style.top = (posY - 64) + 'px';
    crosshair.style.display = 'block';
  }


  imgPos = findPosition(image);
  imageW = image.width;
  imageH = image.height;
  image.addEventListener('click', registerFocalPoint);
  crosshair = document.createElement('img');
  crosshair.setAttribute('src', assets.crosshair);
  crosshair.style.position = 'absolute';
  crosshair.style.display = 'block';
  crosshair.style.pointerEvents = 'none';
  crosshair.style.backgroundColor = 'transparent';
  image.parentNode.appendChild(crosshair);
  image.parentNode.style.position = 'relative';
  displayCrosshair();
};
