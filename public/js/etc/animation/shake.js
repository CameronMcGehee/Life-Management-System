$.fn.shake = function(interval = 100) {
    02
        this.addClass('shaking');
    03
        this.css('transition','all ' + (interval / 100).toString() +'s');
    04
        setTimeout(() => {
    05
            this.css('transform','translateX(-50%)');
    06
        }, interval * 0);
    07
        setTimeout(() => {
    08
            this.css('transform','translateX(50%)');
    09
        }, interval * 1);
    10
        setTimeout(() => {
    11
            this.css('transform','translateX(-25%)');
    12
        }, interval * 2);
    13
        setTimeout(() => {
    14
            this.css('transform','translateX(25%)');
    15
        }, interval * 3);
    16
        setTimeout(() => {
    17
            this.css('transform','translateX(-7%)');
    18
        }, interval * 4);
    19
        setTimeout(() => {
    20
            this.css('transform','translateX(0%)');
    21
        }, interval * 5);
    22
        this.removeClass('shaking');
    23
    };
    