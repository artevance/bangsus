function Clock(clsr)
{
  let today = new Date();

  clsr(checkTime(today.getHours()) + ':' + checkTime(today.getMinutes()) + ':' + checkTime(today.getSeconds()));
  setTimeout(Clock, 500, clsr);
}

function checkTime(i)
{
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}