# Evaluacion Tecnica para puesto de Frontend Developer

## Desarrollado en: 
- LARAVEL 8
- HTML5
- BOOSTRAP COMO FRAMEWORD DE HTML
- JAVASCRIPT
- MYSQL

## Documentacion de lo realizado: 

1. Se procedio a realizar una revision del documento xlsx enviado con la data de de prueba para la evaluacion
2. Se procedio a realizar un analisis para la normalizacion de los datos, es decir, evitar datos duplicados segun se repitan, al ser una data de 1000 registros con tan solo 324 de usuarios unicos, el apartado de los clientes de una Compañia se veria afectado por la cantidad de datos repetidos
3. Se procedio a separar los datos en 2 tablas, una llamada `clients`, con los datos mas importantes de un cliente y otra llamada `datos`, en ella esta el resto de los valores que son comunes entre los distintos clientes de la empresa, para realizar esta operacion procedi a insertar una ultima tabla con el total de los 1000 registros enviados y se realizaron las siguientes consultas SQL para separar la data: 
   - Seleeccion de los usuarios unicos 
    ```sql
    -- Solo me trae los usuarios y la cantidad de veces que se repite su rut
    select id as c from data_examples GROUP by rut HAVING rut > 0;
    ```
    - Para actualizar el lugar de residencia de los clientes utilice:
    ```sql
    select c.id_client, m.id_municipio, r.id_ciudad,e.id_estado
    from data_examples as d 
    inner JOIN ciudades as r on r.ciudad = d.comuna 
    INNER JOIN municipios as m on r.id_municipio = m.id_municipio 
    INNER JOIN estados as e on e.id_estado = m.id_estado 
    INNER JOIN clients as c on c.idcard_client = d.rut 
    where id in (82, 
    767, 789, 763, 935, 891, 901, 830, 47, 857, 733,42, 60,693,835,72, 98,937,914,57, 53,730,766,770,746,782,964,866,994,816,977,163,825,864,855,870,886,911,757,920,729,744,996,820,948,925,824,817,909,947,970,863,921,979,930,907,943,828,52, 93,173,1, 68,836,854,887,769,850,792,892,939,526,972,959,899,810,778,889,851,878,548,877,796,787,928,741,777,867,749,987,755,912,16, 7, 50,969,726,902,800,774,803,783,929,893,990,919,753,727,940,732,790,771,871,844,967,734,971,845,776,411,804,832,997,916,973,779,906,872,740,62, 80,882,797,992,839,868,915,791,840,843,860,841,941,795,931,938,833,858,856,739,735,875,958,822,738,750,983,975,21, 85,918,989,737,995,954,942,842,865,963,751,756,976,984,922,932,762,946,773,754,933,988,834,764,765,772,978,788,974,852,951,945,985,982,927,793,910,949,747,926,862,798,955,805,752,748,759,784,849,760,965,999,873,980,962,880,861,966,742,881,786,876,961,799,934,885,826,819,728,801,846,384,781,859,905,903,917,768,736,956,904,775,991,794,884,924,888,745,883,908,957,981,869,831,815,879,923,829,953,761,952,821,986,743,418,823,838,802,410,553,655,666,389,709,2, 33,472,124,650,807,541,896,809,521,898,660,874,818,895,589,143,758,619,90, 08,105,100,814,811,847,473,806,458,837,95, 31,642,110,604,399,812,936,423,913,498,827,813,599,894,119,158,704,900,848,673) 
    order by id asc;
    ```
    - Obtengo todos los id's de los clientes sin repetir en la tabla data_example que es donde se encuentra toda data enviada como prueba y le digo que me traiga de la tabla `clients` el `id_client`, de la tabla `municipios`, me trae las provincias, de la tabla ciudades, las comunas y de la tabla estados los id_region, y luego con la siguiente consulta procedi a actualizar a los clientes
    ```SQL
    UPDATE `clients` SET  `region` = 25, `comuna` =101, `id_state` =7 WHERE id_client =1;
    ```
     - del mismo modo con los 324 clientes unicos 
        
    > (en mi pais de utiliza Estado, municipio y ciudad, pero luego de buscar en google la distribucion geografica encontre que chile utiliza Region, provoncia y comuna, lo que es muy similar a Venezuela, descargue una lista de internet con las regiones, provincias y comunas de chile y lo cargue a la base de datos)
   
    - Una vez realizados esos 2 procesos procedi a comenzar a crear los filtros, los cuales describo en el documento acontinuacion mas abajo
4. Se procedio a añadir una capa donde tenemos 2 Empresas a la cual les prestamos el servicio. 
5. Descripcion de filtros creados:
   - Filtros por compañia, con un `select` ó _combo box_ donde estan las 2 compañias creadas
   - Filtro por regiones, teniendo una compañia creada, podemos dar inicio al filtrado de esa compañia por la region en la que se encuentren
   - filtro por provinicia, al igual que la region, teniendo la empresa cargada iniciamos el filtro por la provincia de la region antes seleccionada.
   - filtros por comuna, al igual que las 2 anteriores filtramos por las comunas de la provincia seleccionada
   - filtro por montos: tenemos un valor minimo y un valor maximo y utilizando el operador `between` de SQL podemos decirle que nos busque en un rago de y hasta lo cual permite que traiga los valores entre los parametros que le asignemos.
       > Cabe destacar que los filtros son operables entre entre ellos dandole dinamismo al filtro.
