# getJalaliHolidays

PHP script that obtains the date of holidays from the received year and month

## Usage example:

GET request with year and month values:

```
./getJalaliHolidays.php?year=1403&month=1
```

- which obtains the date of holidays in 1403/01 the first month of 1403

Output:

```
{"status":true,"holidays":["1","2","3","4","12","13","23"]}
```
