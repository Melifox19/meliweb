function changeDisplay()
{
  if (document.getElementById('type').value == 'meliruche')
  {
    document.getElementById('meliborneField').style.display = "block";
    document.getElementById('addrMelinetField').style.display = "block";
    document.getElementById('sigfoxField').style.display = "none";
  }
  else
  {
    document.getElementById('meliborneField').style.display = "none";
    document.getElementById('addrMelinetField').style.display = "none";
    document.getElementById('sigfoxField').style.display = "block";
  }
}

$(document).ready(function()
{
  changeDisplay();
});
