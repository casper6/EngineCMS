<?php
  @require_once("config.php"); // Настройки сайта
  @require_once("includes/db.php"); // База данных (функции для работы)
  @require_once("includes/sql_layer.php");
  global $prefix, $db, $style_disable; 

if (!isset($style_disable)) $style_disable = false;

if (isset($_GET['add'])) $add = $_GET['add']; else $add = "";
$name = explode("-", $_GET['files']);
$type = $_GET['type'];

// Добавочный шаблон стиля
if ($add != "") {
  $add = explode("_",$add); // ввести дополнительную проверку
  require_once("page/shablon_style.php");
  $sha = shablon_style_show ($add[0], $add[1]);
} else $sha = "";

// это будет добавлено к любому CSS
$contents = "
.page_tags, .page_another, .page_rating {margin: 10px 0 10px 0;}
.page_nums {margin-bottom: 50px;}
.page_another, .page_comm {font-size:20px;}
.page_socialnetwork, .page_socialnetwork div {display:inline;}

.favorites_ico {background-position: -40px 0px !important; width:16px !important; display:inline-block !important; height:16px !important; background: url('data:image/gif;base64,R0lGODlhwwAQAMQAAPb39/acj+qyHjUuMRFlpWun2p3n9uluYfcOB4U1IASJO/e/p7FbUdLw+HDp9A3e7ux7kiWWyaGorfw/ePrq2/XS0GKEr/5BNY3F5DJ00NPS0q0tMP9tAPsOVQAAAP///yH5BAAAAAAALAAAAADDABAAAAX/4Cd+FTR13QRVY+u+sKsRRPEBG6Lvce/fgNvPlykai8Mk4rK8OA+BxSIAubgYm+wmgM1CtBtGr1CIfMpmVwDFZgeSIo/8Z6ERGqSdHm75eEQ2LgANhEFwL0dHLgM9jDAIUAGSUgsVFBQVCwdWI2BiXQwVXVljEWamaSMnbBOrKBM+cx8Jco4wABl2NgBMeghDFsEWf2cvAAbIDYaHI4lGiwOO0h/RtiMIAZiZFZaXlwABnCJgBwCj5mDMLRBtHZjtED0eCQnR9QkLPbn7Igy+vz2ECfwTqEUDZAaCKFTnDEkLa9RGQLwWoIKTi1AoUQjXwhMOLei0xMCAwQDJk2kq/8ATwa4NCxfzqNUTQS+BGBgFdN3IoUVHDIECCxA05gAhoaN44DTMsOjFtEdRAD558yEbxxEMGECIUlWCJHAQDmSFUdLkUZNkPrRMAYFCEAom2MRrkUCsH5t+GNQkNkIZLhoYAEhQQJjwgAQvhDGSEIyMBaEF+xZ1QLky5UNLoUWLGLEaRGwWRTjZKmLBRnFqbzRwy62CBgAUVot6UeCkiwaoTsxdtowdLKwLBBywiViEBrG0+H4gELgCBjyDC0tvAfSOBg3CyHiILAKA5e8Okg7JrO4FtgVSL0ApfbojqI9hQpJyQQaDiAZpz5his8AQ7wVstMCAFAPI4UFWUgig1/9mI9DwGAAAaCDdhC4INIBrEmTYmFAvNACeZZg15AIHJJI4QoklunAeai1YhRoYG4TEAAUw0laAAYDkh0EEBLTRX3c3ABjgCBYwEECBBgYnhV70tGDHHR9QMOF0FT5GgAYZSqDBB4xxKMgDlPk1yDIwGWjmdWiiOSKKJ6LIgYpRxWDaVeOAEYAGEKywQI30BYJfBDh+kBsrC4nQxm+lHYakBwNAkRUDhxUnAgEDBLPlDVMSBsNjCWAggQiMNabcfWA+oMwQZp6ZppowmCiCq65StAAnU4ngYkdZMHDABq99sEBWu87XQhn3naofj2shaogrc5VGywCQEpfVPU2OEBj/bBr8KNiUmxZgwaevdVmNMQ+UiyOZMKRq4KqsvhArrG+2cF5661XVHlYrAPDFFjRuAeFxL0RQQBANoMQjjyqhEA8AUgSx1ksjPAshBYzWEqmkIlQgwMYbu9UAtzgR8EEwEVIKwEQ3lGsuQgaI14K6crB7XavxvhpvrCLMm/MFE1B16whu/aoFBA2AAcGPLpiSFBqo0KCWwnGlQIXCL9BDDMWMblBTtd1x7HUQIL/AGAHBDCDYyT0YoPLa5aLrB8wyX9oCzh/ACycFF6kHRSZzorbvBltp4Fa2AWhxUwum+IlKTjyK4Eo7r8CgF6QDSMDo1jW1QIHXHINNYQwFSLDZqbg+OMC2yi7HAbfMa6b4pps155wNJt5849a9dW7xQbYVMCy4BiIlbUqgiDttK+QoUAVDPQf8yiTmGAPAuQD5REflpij3AKHap7sNswdxt27i626qqB4HB6SfETdTOIFrjPu2lSsFXQR8cATPabCjHS2UsIoKEIsB9AYoiOBsrD8S+lx5fjAIpMTge+FboIr08ISjLSAsLMICA0ICgZCUIAwwONiTnvSBEAAAOw==') !important;}

.rotate180 {-moz-transform: rotate(180deg); -o-transform: rotate(180deg); -webkit-transform: rotate(180deg); transform: rotate(180deg); }


.ico_like {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAABrklEQVQ4jZ2Rv2tTYRiFnxNuTcA2VdxEByGIUrrku9Ihg6VDRwXRwUEoboou7kLdxEEQlP4FitCpgwgKpSIu4d58Eili7FZ0qQ7FCIk35LjYRRr648znPDy8rxiRer1ek/Qa+GV7odVqtXfrlUYBJF0HXkl6IGlldna2clDAWdsfsyxbsf252+3O7xswPT193Pa87TcApVLpi+0z+wLMzMxUx8bGngMvY4zfAWwfs729q2mapo9s3wUqwFegbPtdtVq9uba2NgAIIXSAk8Cff7sfwDfgvkII/eFweCrGuBVCeCbpRZZlH0bdBmBqamq8UqlcBJZKwJEY4xaApK29xgDr6+td25vARLJX+f+EECaBW8Ac8HbkG0clz/PtPM8fSnoKVA9jcM12avuS7ceHMVgul8uLwFFJ7w8MACiK4hwwXhTFZgKQpulV21eATq1WK29sbPRH6E9KumP7NrDYbrd/K4TQT5LkQlEU5yU1gMvAqqRVSZ8kbTabzZ87kEajMdHr9ZrD4fBGjDFLJD0ZDAa5pAToAAu2a8Cc7XvA6RDCiR1Ar9frSlqKMeYAfwGeY74LU/80+AAAAABJRU5ErkJggg==');}
.ico_user {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAABnklEQVQ4jY2TPYsTURiFn5PrhIBEcUGYgTSKH8UScDKZVLsYLBYWf4Ds9iK29lpYWcj+AF0QKz9WtHBlO4tgF5KM21nYCWl3SUSLmfvaLLIOmeDp7r3nPJz3hSsWKE3Tm977R0AMIGlcFMXjyWQyKHtr5Ytut7vlvd8zsxfOuavANeBlrVZ71+l07pT9On1ot9sX6vX6NzPbHI/Ho1Kr1Hu/XxTF9SzLjhY2CILgvpl9LIcBhsPhEDhwzt1bNsIG8LYc/ltXenPiWQyQtBoEQbYEMAFWlzU4N5/Pf1YBvPe/gPPLAMeNRmOlCuCcWwGOlwEySXEVIM/zG8DXSoCk18BWFUDStpm9qgTkef7ezPq9Xu9SOZym6WVgTdKHSkCWZUeSdrz3z0tvNTN7BjwdjUb/7MCdNsVxvH6yg+0oijphGB5GUXSx1WrtmtmGpMMwDH9Pp9MfgAGo3++fmc1md4EHwAz4BHwGvkvaBDCzAzO7AtySdBs4C+w0m81dJUnyRVJRFMXDRb9tkeI47jrnnphZXUmSDID1/wmWJWnwB0ZulJJZlaGzAAAAAElFTkSuQmCC');}
.ico_rating {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAdNJREFUeNp0Uz1rAkEQnfswSZNcCjGVTTgEDaROYRESwX+gRSq79EIwVmLhQX6B+QnWYmETUknqEDRIOMRCsDvP5vy8zFu8Yz104PH2483szOyuUqlUSFEUkk3TNMDk4QPjY7PZ/DH2NL7vk2VZpNJhM9nhsVarvYMxP6IjnSNdM98xzoPF9XpNjUajud1uqV6vN6vV6jMvP0p+c/b7YrapXC4/TSaTH3byZaxWqxDRPejhhzJUFlzE4/EbnCoDNXc6HcHRPejhh1TU5XKpIFUIZQwGA7JtW3B0D3r4iR4sFoswAAwO3W43LLbdbgvO5/OUTqfFGLcGPxEAkYLIsFQqRaqqUqvVCoMUi0UyTTPUIECQgSpnEADNcV0XtQrGPFpCkMHBAL1ej7LZLJVKJcGYHwuge5631wNYoVAQjLVcLheOA4MefiIDx3Hmo9HoNxaLka7rIeQT5XXooIef6EcymUyx6J7HhvxE+/3+22w2I8MwKJPJvERe8Iz/yud4PB7q7DzkaEN5lx9LJpFIaNPp1GJ+5aUOn96PfjiRwW6u7v7CJQMv7ISv8or5lvHNNU+ZVwyX4eAvoC24nSAA+JRxtmPtwMdDFxcMj7FELxHgX4ABAEURj0dczZHMAAAAAElFTkSuQmCC');}
.ico_minus {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA6klEQVR4Xq2TTYqDQBCF30ArLt0pepn2Mh7EY7hzPWt3AXOSgIsWXcW1f508QjEMBNNiPqiWhlePLqvqx1qLMygeZVny0yiltO/7kHjeQZZlwTRNErxfAWR5nr8Mtm1r0jTVSZLAha7rtDGmoQkN6KijKMK6rnCB2rZttZTARJrAFckRA8zzzMARRC8l0ADjOH408jwPYRj+vZhtLIrC1nVtq6qyn6CGWuY8+V8CXW937EKN6IkYsL8gl98SeyilqH3/D4IggAPy2vNd+N4ccLaNMTqOY7jQ973sA2QXMs72MAyHlom5p9f5Abs97D+FdaFVAAAAAElFTkSuQmCC');}
.ico_plus {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABNklEQVR4Xq1TvWqEQBBeQUWsrPVlvDopgk24JqX4Gj5BCiutQjg40oiBYGtaH0TBzkrBv819e+yibIQLl4FhZnfmG78ZZxVKKblHVJgkSWAKVVVdXdcJ18uZAaZpIsMwcMX5+xI4+L5/LbAsS+E4jmvb9k0E6rp2q6oqUISghSiKaFmWG83znGZZBoUvxYEBljGY55nRXEvf98TzPOanaSrFgREzGMeR6VoAmBfhS3F+VtcJbdtuEsdFfA19M1/TNGJZFjBbBphu13Xk6flFFBjmq33wjuIu+3gnpmkCI7eAqgDti2ALlRlA0lMiAI9Hn9mvs7jDbiD39xkYhiHAbB6rIaJvLpztf/yF/T1QFIV8vr1y2rt7wDYxCIIiDEMax/FNilxggOVv4YDdbprmT48Jsbuf8w8MP0OmQcG1yQAAAABJRU5ErkJggg==');}
.ico_rss {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAmpJREFUeNp0UztoFFEUPe8zs7sShaCFIaIbiwEjCAp+YiP4SWGagBaija5gY2GKlRTCItYWKawCyjYGiyhWKSLaxoAIFkZIkV1sFATFxc3M7Myb5313dyfuggN33ps395577pkzol0LztkMdYoyBayh6K3Z0PPQ2rSZmNXWok6pZSHpVABQdLNuI+GOXDLsP4W9MNsomzZea9dZiAzFynO4y37bgGm8h/myCqM0jFCgJgwuHJjhNAiPblTrACA0NZ44031Dqz5bgQ1bSNeeIXm7gE7mUVc5wMABcm1rPrDST6EPneR6SQDqyDTk2CQ/Z8So87KK+Osmko7a0SEF4h8C4vf9wIqioXnpVDhYyzPrw6fhXa4xkGMTP72GsEEgkeoKTNEhAMk0qGjXg0+kwxK8qVuQo+NIt9YRPplB+nEZorQHhdsvUJoIIElZZtATlQFYFEpyOvgzNRTvrsC/cI/lj5erOYh/5TFKIwl37wOo+VN7H6oCoTbWWHmkMdTB4wwmRw/AbKwi/fwG+mhXFyEpZXMdaahgwh4DYzXirQ+cnLyq8rxubn3iKvyLc9wpWXnETPVUBcXdpsuiP4IgPv5YgMKNRfjXF7m4s3QnL9D7xpFQV/dF3CjesUtQKtsBkOQI7/wc1OQ0h9tnZKb+7O7M2ZpHRNczyrOsQy7i8OUo5gUOIBEMyoKTFlJbZsBOTCIJ/W4hL05oH7U1RqJW11z7qUANNnBG4vG/V4IG+bqsyUxeIWMTdWKJhFT2S6nzOyUKmJhcp2zuwuiXRPhTNh2Dm9k26ukfVQ6NHviVI+P971d2a5OIzP4VYAAztWyOqE9chwAAAABJRU5ErkJggg==');}
.ico_loading {background:url('data:image/gif;base64,R0lGODlhEAAQALMMAKqooJGOhp2bk7e1rZ2bkre1rJCPhqqon8PBudDOxXd1bISCef///wAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFAAAMACwAAAAAEAAQAAAET5DJyYyhmAZ7sxQEs1nMsmACGJKmSaVEOLXnK1PuBADepCiMg/DQ+/2GRI8RKOxJfpTCIJNIYArS6aRajWYZCASDa41Ow+Fx2YMWOyfpTAQAIfkEBQAADAAsAAAAABAAEAAABE6QyckEoZgKe7MEQMUxhoEd6FFdQWlOqTq15SlT9VQM3rQsjMKO5/n9hANixgjc9SQ/CgKRUSgw0ynFapVmGYkEg3v1gsPibg8tfk7CnggAIfkEBQAADAAsAAAAABAAEAAABE2QycnOoZjaA/IsRWV1goCBoMiUJTW8A0XMBPZmM4Ug3hQEjN2uZygahDyP0RBMEpmTRCKzWGCkUkq1SsFOFQrG1tr9gsPc3jnco4A9EQAh+QQFAAAMACwAAAAAEAAQAAAETpDJyUqhmFqbJ0LMIA7McWDfF5LmAVApOLUvLFMmlSTdJAiM3a73+wl5HYKSEET2lBSFIhMIYKRSimFriGIZiwWD2/WCw+Jt7xxeU9qZCAAh+QQFAAAMACwAAAAAEAAQAAAETZDJyRCimFqbZ0rVxgwF9n3hSJbeSQ2rCWIkpSjddBzMfee7nQ/XCfJ+OQYAQFksMgQBxumkEKLSCfVpMDCugqyW2w18xZmuwZycdDsRACH5BAUAAAwALAAAAAAQABAAAARNkMnJUqKYWpunUtXGIAj2feFIlt5JrWybkdSydNNQMLaND7pC79YBFnY+HENHMRgyhwPGaQhQotGm00oQMLBSLYPQ9QIASrLAq5x0OxEAIfkEBQAADAAsAAAAABAAEAAABE2QycmUopham+da1cYkCfZ94UiW3kmtbJuRlGF0E4Iwto3rut6tA9wFAjiJjkIgZAYDTLNJgUIpgqyAcTgwCuACJssAdL3gpLmbpLAzEQA7');}
.ico_calendar {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA3ElEQVR4Xp1PQaqDQAzNDAqCoEUQBEHrStBF8QS9Qm/Q3kSP1CP9G/RvXGqat8hiENHpg8d7SeaFCSmGYUiFHyFDtX80t2qYmZZluazr+oJq/9S867qx73sGxT+hW27nyJm2bVNjzD/9iABfk2/BP3wWSQ5nvAlomobJD26uqipdoHWNHrjnnVxZls4C7YFHnoCiKHxPcHN5nrOwhp71miMgyzJW9fUEJEnifYLmDEwcx1hwFf6h3vPzPBtSaA6IoohVD7zCyVEYhhwEwY08IO/vyBkU1tpRZCJ/TF9TFpEyCDAkNQAAAABJRU5ErkJggg==');}
.ico_eye {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAADaElEQVR4XqWQa2xTdRjGn///XNrTle5iN41rBySDhC8mgDG6zQ/GmDkzSJwa5IMEFUgMjug2155uK24azUxQN9gX1BlvaIIoFxVvidGZDSij3dSyhAR27e2s7UZPu7bn5ukH+IAffZJf3rwfnvd98hDDMPB/xOIO9ff3b6utdR1aZ7c3cjxfIcsZpyzLS5IkLRUKxV/Mh8cBzN8y3E7g9x+uaWpq/NbldjdQQpDNZhGLxZBMJtVIJMqWdlVVAWDNRDQZMgE1QW9v3/rm5uYZh8PRMDE+MTs4+I40MPCG/smnn+PkqdPs19+cwQ/nf9LC4XDETCMBeN/EawLGjGXfvv3+YCaTSY+OfhzjrUK27am2jTa7g5bZy5FezWB+MYqZa9epo6JKaGx86KOLFy5w1dXVDYSQ8xTAS6FQ8OzZc9999d7wSE1XZ+fWpUiMBkPTCF+dwdz8AlLpNDRNQ2hqmt2/f1/XkaGRX0Oh0PcAnmbzhUJsR8uOWGVlxQeUEgsIoOk6Jq+EkM8XSmazhxRKcrtcMHRgbS0rDr47PDp6/NhpqlP2hqIoe3mLYIknEtBUHS3Nj0FRVVy/MXvbXKLjlZeRkCRwnFVLRCNxwVa2jnnk0abi77+NqTAMadPmzRvSqaTAMiyefaYNC4uLWF5exqb6evT5utHS8jhgEHk6ePnExPifsqoYf9Bo+mpqo7tmLBAYm/B6PB9evDx5yixuQQPByNEhBAPj+PnHc3iitTU3Nf33paPHhs4EApfyOjUmdXA3Cfhy9LQftEMgbtVAfTwe35LL5Ny8xVrpcFQ4WZYhhFIjJSVuVlWWy4JNSFIwYU0rTrGryixKB0588Q9984W3bKLXX+ft7nvA19Oz0yeKL3q9vk7RIx4254DP4/F7RPE1r1fc4+nqebi73V/X5/NZGTBWDJ48gvRzV9QFaTZfpIrM6FjRCVKGQZMgegI6EmCYZaIgRkCjVrAJjsmt3FO3mie3GuZNinYL3bJzH7YJTv4uXrbYeEYwONVu6GUsMVSqMyqxUFJgCZFTa7nM/F9Plg78V+TuMnIv30oe3FrLuZw1TMG5wlGi8468ThSmqGk6LWZluTD35bACQbCBgOJOUUrBcQxZL1Qxb9+3m3/1+T2WvYd22w50HLDter2X39X+GbOBAP8CwbSTajCk73UAAAAASUVORK5CYII=');}
.ico_comment {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAABz0lEQVQ4ja2SPYtTURCGn5mbRFMpWLgmKcT8gOTmmmQFi4CNCCJuERYRC220cSvxB9gqSBBEBAUbZUXWQiQWdkaMmg9JoxCwUGLWxpAgckPOOTabJSq6EX2rGWaeGWZ44R8ls0mpVNo+Go2WgGPAQSC+UfoGPBORtcFgsNbtdsNfBuRyuSURuQrUReSeMeb1ZDIZAnietyMSiey31i6LSB5YaTQaDzcHBEFwEThtrS23Wq03xWJxtzFm0Tm3dzwe3+l0Ol+mi/L5fNZauwrcaDQaV8T3/UOqektVi2EY2mg0ehb4qqpPVfVTvV5f//lu3/cTqvpCRE5JEARV59xtEakCl40xF9rt9mCr5wVBcMI5d1KBorX2iYgsA5fmgQFisVhVRBYjwEREomEYrs7eOq8UeKmqR/4WHo/Hh4Hn3sLCwnsRuZZKpR70er3hPHAmk0mq6l1r7YrX7/c/JhIJ65y7mUwmX/V6vQ9/gn3fP+B53mOg0mw2788a6biIVJxz55rN5qNCobDLGDMEMMYkZ4xUAM7/YKSpgiC4DrwF9gFngG0bPev8xsqRaZDNZncCZWDgnHsXj8f31Gq10Vb/0M1AtQx8BirpdProPPB/0Xd73MZ2+hU/igAAAABJRU5ErkJggg==');}
.ico_folder {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAASklEQVQ4jWNgGGjAyMDA8B+HOEkGIGvAZiBO/UwkKMYG/pNqI4YBlLqAgQWbqQT0oAQwNgOIjoGh5wKsBlPsAoqjkWIDcGUmogEAvTYKIxYmSqIAAAAASUVORK5CYII=');}
.ico_readall {background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAAYUlEQVQ4jWP08wv4z0ABYKJE8+AAjMSGwaZNGxixiQ+TMPhKiQEsDAwMXBQZ8OPHNx5KDBgOwNc3YL+fX8BeGN/PL+AAMh9dHh2wMDIysDAwMP5DCP1nYWBggidbTHlUAADYoRflbx7hmAAAAABJRU5ErkJggg==');}
.back_icon, .back_icon2, .back_icon3 {display:inline-block; margin: 5px 20px 5px 0; padding-left: 21px !important; background-repeat:no-repeat; background-position:center left !important;}
.back_icon2 {padding-left: 0 !important;}

.i16 {width:16px; height:16px; display:inline-block; background-repeat:no-repeat;}

.clear {clear:both;}

#comments_refresh {width:32px;position:fixed;top:50%;opacity:0.5;right:2px;}
#comments_refresh:hover {opacity:1;cursor:pointer;}
#comments_refresh .change {color:#666;font-size:12px;text-decoration:none;padding:0px 10px;position:absolute;left:0;top:-15px}
#comments_refresh .refresh {display:block;background:url('data:image/png;base64,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') no-repeat left top;width:32px;margin:2px;height:32px;}
#comments_refresh .refresh.loading {background:url('data:image/gif;base64,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') no-repeat left top;}
#comments_refresh .new {text-decoration:none;display:none;color:#000;font-size:12px;padding:0;padding-top:4px;padding-bottom:6px;width:34px;text-align:center; text-shadow: 0 0 5px #fff;}

span.rur { text-transform: uppercase;} 
span.rur span { position: absolute; overflow: hidden; width: .45em; height: 1em; margin: .2ex 0 0 -.55em;}
span.rur span:before { content: '—'; }

.filter_name {font-size: 20px;}

.another_links {margin: 5px 0 30px;}
.another_link {margin: 10px 0 10px;}

.editorbutton {
  margin-left: 10px; 
  display: inline-block;
  cursor: pointer;
}
.bb1gray {border-bottom: 1px dotted gray;}
.but_bold {font-weight: bold;}
.but_quote {font-style: italic;}
.but_smile {
  writing-mode:tb-rl;
  white-space:nowrap;
  -moz-transform: rotate(90deg);
  -webkit-transform: rotate(90deg);
  -o-transform: rotate(90deg);
  transform: rotate(90deg);
  color: black;
  background:yellow;
  border: 1px gray solid;
  border-radius: 8px;
  height: 16px;
  width: 16px;
  line-height: 16px;
  text-align: center;
  font-size: 10px;
  font-family: Arial;
}

.images figure {background-color: #fff;}
.images figure figcaption h4 {font-size: 13px;}

.w100 {width:100%;}

.opros_otvet {
  margin-top: 10px;
}
.opros_line, .opros_line2 {
  height:16px; 
  margin:0px; 
  padding:0px;
  display: block;
  font: 14px Arial;
  line-height:16px;
  color: black;
}
.opros_line {
  min-width:35px;
  text-align:right; 
  font-weight:bold;
  background: green;
  background: -moz-linear-gradient(left, #ffffff, #ff8585, #ffd985, #fffe85, #9dd797);
  background: -webkit-gradient(linear, left center, right center, from(#ffffff), color-stop(25%, #ff8585), color-stop(50%, #ffd985), color-stop(75%, #fffe85), to(#9dd797));
  }
.opros_line2 { 
  text-align:left; 
  background-color: white;
  float:right;
  padding-left:5px;
  margin-left:5px;
}

.align_center {text-align:center;}

td.raspisanie {background: lightgreen; min-width:5px; padding:0;}
td.raspisanie a:hover div {background: green;}
td.raspisanie_add {background: #ff7373; min-width:5px; padding:0;}
td.raspisanie_add a:hover div {background: red;}
td.raspisanie a:hover, td.raspisanie_add a:hover, .pointer {cursor:pointer;}
div.raspisanie { height:15px; width:100%; min-width:5px; border-left: 1px solid white;}

#zapis_dialog_data {font-weight:bold;}

.filter_interval {font-weight:bold;}

#redactor-tab-control-2 {display:none !important;}
.redactor_toolbar {height: 30px;}
.redactor_toolbar li {padding-left:5px !important;}

.comm_form #avtory, .comm_form #maily, .comm_form #adres, .comm_form #tel, .comm_form #area {width: 99%;}

/* Магазин */
.shop_cards {margin-bottom: 10px; padding: 0 5px 0 5px;}
.shop_card_minifoto {border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; border:1px solid #bbbbbb; float:left; margin-right: 5px; width: 30px; height: 30px;}
.shop_card_price {float:right; margin-left: 5px; text-align:right; max-height: 40px;}
.shop_card { padding-top:10px; padding-bottom:10px; min-height: 55px !important;}
.shop_card_oformlenie {margin-top:10px;}
.shop_card_oformlenie a { width:100%; background: #d0e087; color: black; border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; border-bottom:2px solid #bdc9a2; padding:7px; cursor:pointer; text-decoration:none;}
.shop_card_oformlenie a:hover {background: green; color: white; border-bottom:2px solid #666;}
.shop_card_oformlenie a.disable { background: #bbb; color: white; cursor:default;}
.shop_card_oformlenie a.disable:hover {background: #aaa; color: white; border-bottom:2px solid #bdc9a2;}
a.shop_card_delete {background: #e09487; color: white; border-bottom:2px solid #c9ada2;}
.shop_card {border-bottom: 1px dotted #333;}
.shop_card_price b {display:block; margin-bottom:5px;}
.shop_card_del {cursor:pointer; background: #e09487; border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; padding:1px 5px 1px; cursor:pointer; text-decoration:none; color: white;}
.shop_card_del:hover, a.shop_card_delete:hover {background:darkred; color: white;}
.shop_card_itogo, .shop_card_itogo_price {margin-top:10px; margin-bottom:20px; height: 25px;}
.shop_form_input {width:98%;}
.shop_minimal_itogo_text {margin-top:10px; margin-bottom:20px;}

.radius {
  border: 1px solid #cccccc;
  border-radius: 10px;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
}


a img {border:0;}

.text_link {color: #00f;}
.text_link A:visited {color: #666;}
.text_link A:hover {text-decoration: none; border-bottom: 1px dashed blue;}

.green_link {color: #0f0;}
.red_link {color: #f00;}

.small {font-size: 0.9em;}
.red, .red a {color:red;}
.black, .black a {color:black;}
.bold, .bold a {color:white; background:red;}
.select, .select a {color:black; font-weight:bold; background:#dddddd;}
.calendar_cell {text-align: center; padding:5px; width: 14%; height:30px; float: left; border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;}
.calendar, .calendar_month_year {width:100%;text-align: center; margin-bottom:10px;}
.calendar_month, .calendar_year {display:inline;}

.pl20 {padding-left: 20px}

.hidden, .hide {
  display: none;
}

.error{
  display: inline;
  color: black;
  background-color: pink;  
}

.comm_links, .pages_links {color: black; display:block; margin:10px 0 0 0;padding:0; text-align:center;}
.comm_links a, .pages_links a {color: black; border:1px solid #dddddd; padding:5px;}
.comm_links a:hover, .pages_links a:hover {color: black; background: #dddddd; padding:5px;}

/* Горизонт. меню (без подменю 3 уровня) */
.menu-h { overflow: hidden; }
.menu-h li { float: left; list-style: none; padding: 0 .6em; }

/* Горизонт. меню с подменю - 3 уровня */
.menu-h-d { min-height: 24px; padding:0; margin:0;}
.menu-h-d li { float: left; display: block; position: relative; list-style: none; border: 0;}
.menu-h-d a { text-decoration: none; padding: 1px 8px; display: block; border: 0;}

.menu-h-d ul { display: none; position: absolute; top: 30px; left: -1px; width: 300px; background: #fff; border: 0; padding:0; margin:0;}
.menu-h-d ul ul { left: 100%; top: -1px;  }

.menu-h-d li li { float: none; margin:0; width: 300px;}
.menu-h-d li:hover { background: #ccc; }

.menu-h-d li li a { text-decoration: none;}
.menu-h-d li a { text-decoration: none;}
.menu-h-d li li a:hover { text-decoration: underline;}
.menu-h-d li a:hover { text-decoration: underline;}

.menu-h-d li:hover ul,
.menu-h-d li:hover ul li:hover ul,
.menu-h-d li:hover ul li:hover ul li:hover ul { display: block; }

.menu-h-d li:hover ul ul,
.menu-h-d li:hover ul li:hover ul ul { display: none; }

/* Горизонт. меню, выпадающее вверх (без подменю 3 уровня) */
.menu-h-d.d-up ul { bottom: 22px; }
.menu-h-d.d-up ul ul { bottom: -1px; }

/* Вертикальное меню (без подменю 3 уровня) */
.menu-v { }
.menu-v li { padding: 2px 0; list-style: none; }
.menu-v li ul { padding-left: 1em; margin-top: 2px; }
.menu-v li li { border: none; }

/* Вертикальное меню с подменю - 3 уровня */
.menu-v-d { }
.menu-v-d li { padding: 2px 0; display: block; position: relative; list-style: none; }
.menu-v-d li a { display: block; position: relative; text-decoration: none;}

.menu-v-d li:hover { background: #ccc; }
.menu-v-d a:hover { color: #fff; }

.menu-v-d li ul { display: none; position: absolute; top: -1px; left: 100%; width: 100%; background: #fff; }
.menu-v-d ul ul { left: 100%; }
.menu-v-d li ul li { background: #fff; }

.menu-v-d li:hover ul ul,
.menu-v-d li:hover ul li:hover ul ul { display: none; }

.menu-v-d li:hover ul,
.menu-v-d li:hover ul li:hover ul,
.menu-v-d li:hover ul li:hover ul li:hover ul { display: block; }

.li1menu_link, .table1menu_link { text-decoration:none; }

/* Красивая таблица - стиль table_light */
table.table_light { border-collapse: collapse; border: 1px solid white; }
table.table_light td { border: 1px dashed #66bbdd; padding: .5em; color: black; }
table.table_light thead th, table.table_light tfoot th { border: 1px solid #A85070; text-align: left; background: #e9f2fc; color: black; padding-top:6px; }
table.table_light tbody td a { background: transparent; color: #0e5db6; }
table.table_light tbody td a:hover { background: transparent; color: red; }
table.table_light tbody th a { background: transparent; color: #0e5db6; }
table.table_light tbody th a:hover { background: transparent; color: #0e5db6; }
table.table_light tbody th, table.table_light tbody td { vertical-align: top; text-align: left; }
table.table_light tfoot td { border: 1px solid #38160C; padding-top:6px; }
table.table_light tbody tr:hover { background: #e9f2fc; }
table.table_light tbody tr:hover th, table.table_light tbody tr.odd:hover th { background: #e9f2fc; }

#menu { -webkit-padding-start: 0px; }

.img_left {float: left; margin-right: 10px; margin-bottom: 10px;}
.img_right {float: right; margin-left: 10px; margin-bottom: 10px;}

.comm_label_textarea {padding-top:10px;}

.punkt_active {font-weight: bold;}
.button {cursor:pointer; text-decoration: underline;}
.spoiler_link {cursor:pointer; text-decoration: none; border-bottom: 1px dashed;}

.show_block { border: 2px dotted green; padding:1px;  border-radius: 5px;  -moz-border-radius: 5px;  -webkit-border-radius: 5px;}
.show_block_title {background: #efefef; color: black; padding:2px; padding-left:5px; border: 0;}

#redact {position:fixed; background: white; top:5px; display:none; floaf:right; right:5px; z-index:3000; width:330px;}
#redact_show {position:fixed; top:10px; floaf:right; right:25px; z-index:3000; width:18px;}
.ad_button {width:320px; height:32px; display:block; vertical-align:center; margin:5px; line-height:1em !important; font-size:14px !important; padding-top:10px; text-decoration:none; color: #666; font-weight:normal;}
.ad_button:hover {background: #ddd; color: black; border-radius:10px;}
.ad_icon {height:32px; display:block; float:left; margin-top:-10px; margin-right:5px;}

.nav-tabs ul li, .tabs ul li {background:none !important; padding: 0 !important;}
ul.tabs {margin:10px 0 -1px 0 !important; padding: 0 !important;}
ul.tabs li {background:none !important; padding: 0 !important; margin: 0 !important; }
.tabs ul li.ui-tabs-active {background:white !important; }
.nav-tabs, .nav-tabs ul {height:32px !important; }
.nav-tabs {width:100% !important;}
.ui-widget-header, ul.tabs li.current {border-bottom:0 !important;}

/* Рейтинг - 5 звезд, для страниц ***** */
/* Настройка размера звезд — через width и height */
.star-rating {
  width: 100px;
  height: 20px;
  font-size: 0;
  white-space: nowrap;
  display: inline-block;
  overflow: hidden;
  position: relative;
  background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjREREREREIiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
  background-size: contain;
}
.star-rating i {
  opacity: 0;
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 20% !important;
  z-index: 1;
  background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjRkZERjg4IiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
  background-size: contain;
}
.star-rating input {
  cursor:pointer;
  -moz-appearance: none;
  -webkit-appearance: none;
  opacity: 0;
  display: inline-block;
  width: 20% !important;
  height: 100%;
  margin: 0 !important;
  padding: 0 !important;
  z-index: 2;
  position: relative;
}
.star-rating input:hover + i, .star-rating input:checked + i {opacity: 1;}
.star-rating i ~ i {width: 40% !important;}
.star-rating i ~ i ~ i {width: 60% !important;}
.star-rating i ~ i ~ i ~ i {width: 80% !important;}
.star-rating i ~ i ~ i ~ i ~ i {width: 100% !important;}

.ui-dialog {min-width:450px;}
.ui-datepicker th, .ui-widget-header { font-weight: normal; }
.ui-state-default {background: lightgreen !important;}
.ui-state-disabled .ui-state-default {background: white !important;}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, 
.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight,
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {text-align: center; border-radius: 10px; border: 1px solid white;}
.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {border: 1px solid green;}
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {background: green !important; color: white !important;}
";


if ($style_disable == false) $contents .= "
/* Предустановки */

.block_title {display:block; margin-top:20px;}
a.block_title {margin-top:0;}
.block_open_text {display:block;}

.cat_page_comments {margin-right: 10px;}

.table_left {float: left; margin-right: 10px; width: 10%; border:0;}
.table_right {float: right; margin-left: 10px; width: 20%; border:0;}
table[align=left] {margin-right: 10px;}
table[align=right] {margin-left: 10px;}

.all_width, .main_mail_input {width:100%;}
.main_mail_form, .main_search_form, .add, .main_search_form {display:inline;}

input {
  transition: all 0.30s ease-in-out;
  -webkit-transition: all 0.30s ease-in-out;
  -moz-transition: all 0.30s ease-in-out;
  outline:none;
}
input:focus {
  border:#35a5e5 1px solid;
  box-shadow: 0 0 5px rgba(81, 203, 238, 1);
  -webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
  -moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
}
input[type=radio] {
  margin-right:10px;
  margin-left:5px;
  margin-top: 0 !important;
  padding-top: 0 !important;
}
input[type=search] {
  -webkit-appearance: textfield;
  -webkit-box-sizing: content-box;
  font-family: inherit;
  font-size: 100%;
}
input::-webkit-search-decoration,
input::-webkit-search-cancel-button {
  display: none;
}
input[type=search] {
  background: #ededed url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAB7UlEQVQ4jZWSzUtUURiHn/fcY3NXgmCbiWpANzXIjPfMQASBiyCJCqLatIratKlAsmXrKCP8A2oXlds+sJZRCXY/ECUNpHYzrZLBjdfx3reNBukV7bc6i/M85/1xXmGXVKvVA77vXxeRG6o6AKCqy8aYp6r6LIqiLoAUwbVa7ZC19rWq/gAmu93uAoC1dsjzvDuqWsnz/HySJC2zHR4ZGfGtte+A53EcXwbaPT09VwCSJPkYhuElEXlljHk7ODhY8rYL+vv7b4lIXxRFt4MguGaMqeV5/mZubu7X1p1Wq/W5XC5f8H2/zxY0uJrn+ZhzbkBE+sIwfFJUU0QeqeqDHRVU9djGxkYsIqdV9WURDFAqlSLgeNEEGUCaplPz8/Mruwm2smMCYMlaG+wFp2nqgG9FgiljzPheL6vqOPDin19oNpuHVXUCOFgul0vtdnumCG40GveAk51O5+bfRXLOHQGmVXVMRELgk6ouAJO9vb1f0zSVtbW1ZuEiDQ8PHwXei8jdOI6nVdUBuYjMAI9XV1d/r6+vr4jIBPABOJEkSQuAer1ecc59D4LgHEAQBKPOucVN6d5pNBpfgiC4uHk+65xbqtfrlX3BgDjnWp7nDWVZdgp46HnemdnZ2Z/7FVjgfpZliyKyLCKj/wMD/AGInMv7GD5VIgAAAABJRU5ErkJggg==') no-repeat 3px center;
  border: solid 1px #ccc;
  padding: 2px 3px 2px 20px;
  width: 80px; /* Ширина по умолчанию */
  -webkit-border-radius: 10em;
  -moz-border-radius: 10em;
  border-radius: 10em;
  -webkit-transition: all .5s;
  -moz-transition: all .5s;
  transition: all .5s;
}
input[type=search]:focus {
  width: 150px; /* Ширина при наличии фокуса ввода */
  background-color: #fff;
  border-color: #6dcff6;
  -webkit-box-shadow: 0 0 5px rgba(109,207,246,.5);
  -moz-box-shadow: 0 0 5px rgba(109,207,246,.5);
  box-shadow: 0 0 5px rgba(109,207,246,.5); /* Эффект свечения */
}
.main_search_button {display:none !important;}
a.search_razdel_link, a.search_papka_link {color:gray !important;}
a.search_page_link {font-size:20px !important;}

.timer {
	margin: 20px auto;
	width: 400px;
	height: 77px;
	overflow: hidden;
	margin: auto;
	clear: both;
	display: block;
}
.cntSeparator {
	font: 54px/54px Georgia,\"Times New Roman\",Times,serif;
	margin: 10px 11px;
	color: black;
}
";


$contents .= $sha;

$n = count($name);
if ($n > 0) {
	for ($x=0; $x < $n; $x++) {
	$i = intval($name[$x]);
     $sql = "select `text` from ".$prefix."_mainpage where `id`='".$i."' and (`type`='1' or (`type`='3' and `name`='31')) and `tables`='pages' and `color`='0'";
     $result = $db->sql_query($sql);
     $row = $db->sql_fetchrow($result);
     $contents .= "\n".$row['text']; 
	}
}

if ($type == 'js') {
  for ($i = 1; $i < 10; $i++) {
    $contents = str_replace("\n\n", "\n", $contents); //Удаляем переносы строк
    $contents = str_replace("\r\r", "\r", $contents); //Удаляем переносы строк
    $contents = str_replace("\r\n\r\n", "\r\n", $contents); //Удаляем переносы строк
  }
  header ("Content-Type: text/javascript");
} elseif ($type == 'css') {
$contents = str_replace("color:white","color:#ffffff",$contents); // Заменим основные цвета...
$contents = str_replace("color:black","color:#000000",$contents);
$contents = str_replace("color:red","color:#ff0000",$contents);
$contents = str_replace("color:green","color:#00ff00",$contents);
$contents = str_replace("color:blue","color:#0000ff",$contents);
$contents = preg_replace('/\/\*.*?\*\//s', ' ', $contents); // Удаляем все комментарии
$contents = str_replace("\r", " ", str_replace("\n", " ", $contents)); //Удаляем переносы строк
$contents = str_replace(chr(9), "", $contents); //Удаляем табуляцию
$contents = str_replace(" }", "}", $contents); //Удаляем пробелы...
$contents = str_replace(" {", "{", $contents);
$contents = str_replace("{ ", "{", $contents);
$contents = str_replace("} ", "}", $contents);
$contents = str_replace("; ", ";", $contents);
$contents = str_replace(" ;", ";", $contents);
$contents = str_replace(" :", ":", $contents);
$contents = str_replace(": ", ":", $contents);
$contents = str_replace("+ ", "+", $contents);
$contents = str_replace(" +", "+", $contents);
$contents = str_replace("= ", "=", $contents);
$contents = str_replace(" =", "=", $contents);
$contents = str_replace("- ", "-", $contents);
$contents = str_replace("/ ", "/", $contents);
$contents = str_replace(" /", "/", $contents);
$contents = str_replace(", ", ",", $contents);
$contents = str_replace(" ,", ",", $contents);
$contents = str_replace("  ", " ", $contents);
header ("Content-Type: text/css");
}
echo $contents;
?>	
