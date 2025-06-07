using BackendAPI.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Text.Json;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace BackendAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class EntradaDeDocumentosController : ControllerBase
    {
        private readonly AppDbContext _context;

        public EntradaDeDocumentosController(AppDbContext context)
        {
            _context = context;
        }

        // Obtener todas las entradas de documentos
        [HttpGet]
        public async Task<ActionResult<IEnumerable<object>>> GetEntradas()
        {
            return await _context.EntradaDeDocumentos
                .Select(e => new 
                { 
                    e.NumeroDocumento,
                    e.NumeroFacturaAPagar, // Usa el nombre correcto
                    e.FechaDocumento,
                    e.Monto,
                    e.FechaRegistro,
                    e.Proveedor,
                    e.Estado 
                })
                .ToListAsync();
        }

        // Obtener una entrada de documento por ID
        [HttpGet("{id}")]
        public async Task<ActionResult<object>> GetEntrada(int id)
        {
            var entrada = await _context.EntradaDeDocumentos
                .Where(e => e.NumeroDocumento == id)
                .Select(e => new 
                { 
                    e.NumeroDocumento,
                    e.NumeroFacturaAPagar,
                    e.FechaDocumento,
                    e.Monto,
                    e.FechaRegistro,
                    e.Proveedor,
                    e.Estado 
                })
                .FirstOrDefaultAsync();

            if (entrada == null) return NotFound();
            return Ok(entrada);
        }

[HttpGet("Pendientes")]
public async Task<ActionResult<IEnumerable<EntradaDeDocumentos>>> GetDocumentosPendientesPorRango([FromQuery] DateTime desde, [FromQuery] DateTime hasta)
{
    var documentosPendientes = await _context.EntradaDeDocumentos
        .Where(e => e.Estado == "Pendiente" && e.FechaDocumento >= desde && e.FechaDocumento <= hasta)
        .ToListAsync();

    return documentosPendientes;
}

[HttpPut("Estado/{id}")]
public async Task<IActionResult> ActualizarEstado(int id, [FromBody] JsonElement json)
{
    var entrada = await _context.EntradaDeDocumentos.FindAsync(id);
    if (entrada == null)
    {
        return NotFound(new { message = "Documento no encontrado." });
    }

    if (json.ValueKind != JsonValueKind.String)
    {
        return BadRequest(new { message = "El estado debe ser una cadena." });
    }

    entrada.Estado = json.GetString();

    try
    {
        await _context.SaveChangesAsync();
        return NoContent();
    }
    catch (Exception ex)
    {
        return BadRequest(new { message = "Error al actualizar el estado", error = ex.Message });
    }
}





        // Crear una nueva entrada de documento
        [HttpPost]
        public async Task<ActionResult<EntradaDeDocumentos>> PostEntrada(EntradaDeDocumentos entrada)
        {
            try
            {
                _context.EntradaDeDocumentos.Add(entrada);
                await _context.SaveChangesAsync();
                return CreatedAtAction(nameof(GetEntrada), new { id = entrada.NumeroDocumento }, entrada);
            }
            catch (DbUpdateException ex)
            {
                return BadRequest(new { message = "Error al guardar en la base de datos", error = ex.InnerException?.Message });
            }
        }

        // Editar una entrada de documento
        [HttpPut("{id}")]
        public async Task<IActionResult> PutEntrada(int id, EntradaDeDocumentos entrada)
        {
            if (id != entrada.NumeroDocumento) return BadRequest(new { message = "El ID no coincide" });

            _context.Entry(entrada).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
                return NoContent();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!_context.EntradaDeDocumentos.Any(e => e.NumeroDocumento == id))
                {
                    return NotFound(new { message = "No se encontró la entrada" });
                }
                throw;
            }
        }

        // Eliminar una entrada de documento
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteEntrada(int id)
        {
            var entrada = await _context.EntradaDeDocumentos.FindAsync(id);
            if (entrada == null) return NotFound(new { message = "Entrada no encontrada" });

            _context.EntradaDeDocumentos.Remove(entrada);
            await _context.SaveChangesAsync();
            return NoContent();
        }
    }
}
