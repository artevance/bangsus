console.log('BangsusSys Assets Log: PageSpinner.js is loaded');

class PageSpinner
{
  constructor(id)
  {
    this.id = id;
  }
  start()
  {
    $('div#' + this.id).modal('show');
  }
  stop()
  {
    $('div#' + this.id).modal('hide');
  }
}