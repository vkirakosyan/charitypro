function _url(path = '') {
  if(path.length > 0){
    path = path[0] == '/' ? path : '/' + path;
  }

  path = path.replace(/\/\//g, '/');

  var index = path.indexOf('http') > -1 ? path.indexOf('http') : path.indexOf('www.');
  if(index > -1){
    let url = path.slice(index);

    return url;
  }

  let url = base_url + path;

  return url;
}

function getURLParameter(search) {
  var sPageURL = window.location.hash.substring(1);
  var sURLVariables = sPageURL.split('&');
  var val = 0;
  for(param of sURLVariables){
    var sParameterName = param.split('=');
    if(sParameterName[0] == search){
      val = sParameterName[1];
      continue;
    }
  }
  return val;
}

function months()
{
  return {
    short: {
      Jan: 'Հուն',
      Feb: 'Փետ',
      Mar: 'Մար',
      Apr: 'Ապր',
      May: 'Մայ',
      Jun: 'Հուն',
      Jul: 'Հուլ',
      Aug: 'Օգս',
      Sep: 'Սեպ',
      Oct: 'Հոկ',
      Nov: 'Նոյ',
      Dec: 'Դեկ',
    },
    long: {
      Jan: 'Հունվար',
      Feb: 'Փոտրվար',
      Mar: 'Մարտ',
      Apr: 'Ապրիլ',
      May: 'Մայիս',
      Jun: 'Հունիս',
      Jul: 'Հուլիս',
      Aug: 'Օգոստոս',
      Sep: 'Սեպտեմբեր',
      Oct: 'Հոկտեմբեր',
      Nov: 'Նոյեմբեր',
      Dec: 'Դեկտեմբեր'
    }
  };
}

function weekDays()
{
  return {
    short: {
      Sun: 'Կիր',
      Mon: 'Երկ',
      Tue: 'Երք',
      Wed: 'Չոր',
      Thu: 'Հինգ',
      Fri: 'Ուրբ',
      Sat: 'Շաբ'
    },
    long: {

    }
  };
}

function getMonthWeekdays(key) {
  var monthsandweekdays = Object.assign(months().short, weekDays().short);

  return monthsandweekdays[key]; 
}