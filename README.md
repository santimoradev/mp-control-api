## DATABSE

### Invoices
- id
- campaign_id // Campaña
- mall_id // Plaza
- client_id // Cliente
- store_id // Tiend
- user_id // Quien registro la factura
- total // Total de factura
- is_sales_note // Es nota de venta
- voucher // Numero de factura o comprobante
- issued_at // Fecha de emision

> **NOTA** Posibilidad de guardar el voucher como NOTA DE VENTA, que no necesita un registro se necesitatb que al guardar un local con un numero de serie que se repita en varios locales, se debe seleccionar el local al que se va a guardar

### Balances
- id
- mall_id
- campaign_id
- client_id
- total
- swap
- available

### Clients
- id 
- type_document
- number_document
- full_name
- email
- telephone
- address
> **NOTA** El saldo no es acumulable en las campañas ni en las plazas


### Malls
- id
- name

### Stores
- id
- mall_id
- name
- is_sales_note // Es nota de venta
- serie
- status

### Campaings
- id
- mall_id
- name
- coupon_value
- status
- started_at
- finished_at
- status

> **NOTA**  las campañas son por plaza, posibilidad de crear varias campañas asignadas por plaza

### Coupons
- id
- user_id
- mall_id
- client_id
- campaign_id
- is_printed // true or false
- amount // Cantidad de cupones que se imprimieron
- value // Valor por cada cupon
- total // Valor total de los cupones impresos
- status // 1 Printed 2 Pending 3 For Print 

> **NOTA** Los cupones deberian generar para luego imprimirse y en caso de que ocurra algun  incoveniente se procederia a volver a imprimir, pero con supervision de un administrador, Se agrega el campo user_id para identificar que usuario creo los cupones

### Reprint
- id
- user_id
- approver_user_id
- coupon_id
- status // 

> **NOTA** Esto se registra cuando el aprobador hace una solicitud para crear una reimpresion, y solo se hace cuando el cupon ya esta impreso

> **NOTA** Status 1 APROBADO, 0 NO APROBADO


> **Roles** 
- Agregar varios roles de usuario
  - Admin
  - Supervisor
  - Digitador
