console.log('BangsusSys Assets Log: BaseUrl.js is loaded');

class BaseUrl
{
  constructor(baseUrl)
  {
    this.baseUrl = baseUrl;
  }
  url(url)
  {
    return this.baseUrl.concat(url);
  }
}