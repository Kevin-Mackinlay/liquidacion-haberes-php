Sistema de Liquidación de Haberes – PHP
Descripción

Aplicación desarrollada en PHP que permite visualizar recibos de haberes desde una base de datos y generar los mismos en formato PDF.

El sistema reutiliza una única vista HTML tanto para la visualización web como para la generación del PDF, aplicando buenas prácticas de separación de responsabilidades y control de entorno.

Funcionalidades

Consulta de recibos por empleado y período.

Visualización del recibo en navegador.

Generación de recibos en PDF mediante Dompdf.

Guardado automático de los PDF generados en el servidor.

Descarga del PDF desde la interfaz web.

Reutilización de la vista HTML para web y PDF.

Tecnologías

PHP 8

PDO

MySQL / MariaDB

Composer

Dompdf

HTML y CSS

Uso

Visualización del recibo:

/public/recibo.php?empleado_id=1&periodo=202601


Generación del PDF:

/public/generar_pdf.php?empleado_id=1&periodo=202601

Estructura básica
public/
  recibo.php
  generar_pdf.php
  recibos/
src/
vendor/

Estado

Proyecto funcional y finalizado.
Listo para integrarse como módulo en un sistema administrativo.

Autor

Kevin Mackinlay

-------------   --------------    ----    ---------------    ---------------    --------------------

Payroll Receipt System – PHP
Overview

This project is a PHP-based payroll module that allows viewing employee payroll receipts from a database and generating them as PDF files.

The same HTML view is reused for both web display and PDF generation, ensuring consistency and maintainability.

Features

Payroll receipt retrieval by employee and period.

Web-based receipt visualization.

PDF generation using Dompdf.

Automatic server-side storage of generated PDFs.

PDF download from the web interface.

Single HTML view reused for web and PDF output.

Technologies

PHP 8

PDO

MySQL / MariaDB

Composer

Dompdf

HTML & CSS

Usage

View receipt in browser:

/public/recibo.php?empleado_id=1&periodo=202601


Generate PDF:

/public/generar_pdf.php?empleado_id=1&periodo=202601

Project Status

Feature-complete and ready to be integrated as a module within an administrative system.

Author

Kevin Mackinlay
