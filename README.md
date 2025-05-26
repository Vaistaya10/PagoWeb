# Procedimientos para implementacion #

1. Clonar repositorio
```
git clone https://github.com/Vaistaya10/PagoWeb.git
```
2. ejecutar Xammp

3. Restaure la BD y los sp(database > db.sql y sp.sql)
```sql
CREATE DATABASE prestamos;
USE prestamos;

CREATE TABLE beneficiarios(
idbeneficiario 	INT 		AUTO_INCREMENT PRIMARY KEY,
apellidos 		VARCHAR(50) NOT NULL,
nombres 		VARCHAR(50) NOT NULL,
dni				CHAR(8) 	NOT NULL,
telefono 		CHAR(9) 	NOT NULL,
direccion 		VARCHAR(90) NOT NULL,
creado 			DATETIME 	NOT NULL DEFAULT NOW(),
modificado 		DATETIME    NOT NULL,
CONSTRAINT uk_dni_ben UNIQUE (dni)
)ENGINE = INNODB;

CREATE TABLE contratos(
idcontrato 	    INT 			AUTO_INCREMENT PRIMARY KEY,
idbeneficiario 	INT 			NOT NULL,
monto 			DECIMAL(7,2) 	NOT NULL,
interes 		DECIMAL(5,2) 	NOT NULL,
fechainicio 	DATE 			NOT NULL,
diapago 		TINYINT 		NOT NULL,
numcuotas 		TINYINT 		NOT NULL COMMENT 'expresado en meses',
estado 			ENUM('ACT','FIN') NOT NULL DEFAULT 'ACT' COMMENT 'ACT = Activo, FIN = Finalizo',
creado 			DATETIME 	NOT NULL DEFAULT NOW(),
modificado 		DATETIME    NOT NULL,
CONSTRAINT fk_idbeneficiario_con FOREIGN KEY (idbeneficiario) REFERENCES beneficiarios (idbeneficiario)
)ENGINE = INNODB;

CREATE TABLE pagos(
idpago 			INT 				AUTO_INCREMENT PRIMARY KEY,
idcontrato 		INT 				NOT NULL,
numcuota		TINYINT 			NOT NULL COMMENT 'se debe cancelar la cuota en su totalidad sin AMORTIZACIONES',
fechapago 		DATETIME 			NULL COMMENT 'esta es la fecha efectiva de pago',
monto 			DECIMAL(7,2) 		NOT NULL,
penalidad 		DECIMAL(7,2) 		NOT NULL DEFAULT 0 COMMENT '10% valor cuota',
medio 			ENUM('EFC','DEP')	NULL COMMENT 'EFC = Efectivo, DEP = Depósito',
CONSTRAINT fk_idcontrato_pag FOREIGN KEY (idcontrato) REFERENCES contratos (idcontrato),
CONSTRAINT uk_numcuota_pag UNIQUE (idcontrato,numcuota)
) ENGINE = INNODB;
```

```sql
USE prestamos;

DELIMITER $$

CREATE PROCEDURE spGetBeneficiariosContrato()
BEGIN
  SELECT
    b.idbeneficiario,
    b.apellidos,
    b.nombres,
    b.dni,
    b.telefono,
    b.direccion,
    c.contrato_reciente,
    c.fechainicio
  FROM beneficiarios b
  LEFT JOIN (
    SELECT
      idbeneficiario,
      idcontrato AS contrato_reciente,
      fechainicio
    FROM contratos
    WHERE (idbeneficiario, fechainicio) IN (
      SELECT
        idbeneficiario,
        MAX(fechainicio) AS fechainicio
      FROM contratos
      GROUP BY idbeneficiario
    )
  ) AS c ON b.idbeneficiario = c.idbeneficiario
  ORDER BY b.apellidos, b.nombres;
END$$


DELIMITER $$

CREATE PROCEDURE spGetContratosActivos()
BEGIN

  SELECT
    c.idcontrato,
    b.idbeneficiario,
    CONCAT(b.apellidos, ', ', b.nombres) AS beneficiario,
    c.monto,
    c.interes,
    c.fechainicio,
    c.diapago,
    c.numcuotas
  FROM contratos c
  JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario
  WHERE c.estado = 'ACT'
  ORDER BY c.fechainicio DESC;
END$$

DELIMITER $$
CREATE PROCEDURE spGetContratoById(
  IN p_idcontrato INT
)
BEGIN
  SELECT
    idcontrato,
    idbeneficiario,
    monto,
    interes,
    fechainicio,
    diapago,
    numcuotas,
    estado
  FROM contratos
  WHERE idcontrato = p_idcontrato;
END$$

DELIMITER $$

CREATE PROCEDURE spCreateBeneficiario(
  IN _apellidos VARCHAR(50),
  IN _nombres   VARCHAR(50),
  IN _dni       CHAR(8),
  IN _telefono  CHAR(9),
  IN _direccion VARCHAR(90)
)
BEGIN
  INSERT INTO beneficiarios
    (apellidos, nombres, dni, telefono, direccion, creado)
  VALUES
    (_apellidos, _nombres, _dni, _telefono, _direccion, NOW());
END$$

DELIMITER $$

CREATE PROCEDURE spCreateContrato(
  IN _idbeneficiario INT,
  IN _monto          DECIMAL(7,2),
  IN _interes        DECIMAL(5,2),
  IN _fechainicio    DATE,
  IN _diapago        TINYINT,
  IN _numcuotas      TINYINT
)
BEGIN
  INSERT INTO contratos
    (idbeneficiario, monto, interes, fechainicio, diapago, numcuotas, estado, creado)
  VALUES
    (p_idbeneficiario, p_monto, p_interes, p_fechainicio, p_diapago, p_numcuotas, 'ACT', NOW());
END$$

-- call spGetPagosByContrato(1)

DELIMITER $$
CREATE PROCEDURE spGetPagosByContrato(
  IN _idcontrato INT
)
BEGIN
  SELECT
    idpago,
    idcontrato,
    numcuota,
    fechapago,
    monto,
    penalidad,
    medio
  FROM pagos
  WHERE idcontrato = _idcontrato
  ORDER BY numcuota;
END$$

DELIMITER $$
CREATE PROCEDURE spRegisterPago(
  IN  _idcontrato  INT,
  IN  _numcuota    TINYINT,
  IN  _fechapago   DATETIME,
  IN  _monto       DECIMAL(7,2),
  IN  _penalidad   DECIMAL(7,2),
  IN  _medio       ENUM('EFC','DEP')
)
BEGIN
    INSERT INTO pagos
      (idcontrato, numcuota, fechapago, monto, penalidad, medio)
    VALUES
      (_idcontrato, _numcuota, _fechapago, _monto, _penalidad, _medio);
END$$


```
