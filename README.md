Plataforma de Autogestión de Clases Particulares
Aplicación web para la autogestión de clases particulares con un horario virtual asíncrono del profesor.

Objetivos del Proyecto
1. Implementar Horario Virtual Síncrono: Desarrollar una funcionalidad para que los profesores definan y publiquen sus plazos de disponibilidad de clases o módulos.
2. Optimizar la Administración de Alumnos: Proveer herramientas para que el profesor pueda gestionar la lista de alumnos inscritos en sus diferentes sesiones.
3. Evitar la Intervención Financiera: Asegurar que la aplicación no incorpore pasarelas de pago ni almacene datos bancarios, dejando la transacción económica como un acuerdo privado y externo.


Módulos a Implementar

1. Módulo de Autenticación: Login para acceso con roles de Profesor y Alumno.
2. Módulo de Gestión de Horarios: Permite al Profesor agregar, editar y eliminar horarios, y reprogramar citas.
3. Módulo de Reserva de Citas: Permite al Alumno ver el calendario de disponibilidad del profesor y reservar una hora.
4. Módulo de Registro de Pagos: Recepción de datos.
5. Módulo de Reporte/Dashboard: Resumen de citas, verificación de pagos para el profesor y resumen/factura para el alumno.

## Guía de Desarrollo Rápida
| Tarea | Responsable(s) | Carpeta de Enfoque |

| **Backend/API** | Beyker, Fernando | `app/Controllers`, `app Models` |
| **Frontend/UI** | Jesus | `public/css`, `public/js`, `app/Views` |
| **Base de Datos** | Maykel | Diseño de tablas y esquemas |

**Para empezar:** Clonen este repositorio y sigan la convención de carpetas.

**Sintaxis de carpetas:** MVC (MODELS / VIEWS / CONTROLLERS )
**Models:** Administra los datos y la lógica del negocio. (El "qué").
**Views:** Presenta los datos al usuario. (El "cómo se ve").
**Controllers:** Maneja la entrada del usuario y coordina al Modelo y la Vista. (El "cómo se procesa").
