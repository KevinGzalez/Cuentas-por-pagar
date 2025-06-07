using Microsoft.AspNetCore.Mvc;
using BackendAPI.Models;
using Microsoft.EntityFrameworkCore;

[Route("api/[controller]")]
[ApiController]
public class ProveedoresController : ControllerBase
{
    private readonly AppDbContext _context;

    public ProveedoresController(AppDbContext context)
    {
        _context = context;
    }

    // Obtener todos los proveedores
    [HttpGet]
    public async Task<ActionResult<IEnumerable<Proveedor>>> GetProveedores()
    {
        var proveedores = await _context.Proveedores.ToListAsync();

        if (proveedores == null || proveedores.Count == 0)
        {
            return NoContent(); // 204 No Content
        }

        return Ok(proveedores);
    }

    // Obtener un proveedor por ID
    [HttpGet("{id}")]
    public async Task<ActionResult<Proveedor>> GetProveedor(int id)
    {
        var proveedor = await _context.Proveedores.FindAsync(id);

        if (proveedor == null)
        {
            return NotFound(new { message = "Proveedor no encontrado." });
        }

        return Ok(proveedor);
    }

    // Crear un nuevo proveedor
    [HttpPost]
    public async Task<ActionResult<Proveedor>> PostProveedor(Proveedor proveedor)
    {
        if (proveedor.CedulaRNC.Length != 11)
        {
            return BadRequest(new { message = "La cédula debe tener exactamente 11 dígitos." });
        }

        try
        {
            _context.Proveedores.Add(proveedor);
            await _context.SaveChangesAsync();
            return CreatedAtAction(nameof(GetProveedor), new { id = proveedor.Identificador }, proveedor);
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = "Error al guardar el proveedor", error = ex.Message });
        }
    }

    // Actualizar un proveedor
    [HttpPut("{id}")]
    public async Task<IActionResult> PutProveedor(int id, Proveedor proveedor)
    {
        if (id != proveedor.Identificador)
        {
            return BadRequest(new { message = "El ID no coincide con el proveedor enviado." });
        }

        _context.Entry(proveedor).State = EntityState.Modified;

        try
        {
            await _context.SaveChangesAsync();
            return Ok(proveedor);
        }
        catch (DbUpdateConcurrencyException)
        {
            if (!_context.Proveedores.Any(e => e.Identificador == id))
            {
                return NotFound(new { message = "El proveedor no existe o ya ha sido modificado." });
            }
            throw;
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = "Error al actualizar el proveedor", error = ex.Message });
        }
    }

    // Eliminar un proveedor
    [HttpDelete("{id}")]
    public async Task<IActionResult> DeleteProveedor(int id)
    {
        var proveedor = await _context.Proveedores.FindAsync(id);
        if (proveedor == null)
        {
            return NotFound(new { message = "Proveedor no encontrado." });
        }

        try
        {
            _context.Proveedores.Remove(proveedor);
            await _context.SaveChangesAsync();
            return Ok(new { message = "Proveedor eliminado correctamente." });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = "Error al eliminar el proveedor", error = ex.Message });
        }
    }
}
