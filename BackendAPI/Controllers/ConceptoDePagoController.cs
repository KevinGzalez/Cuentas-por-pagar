using BackendAPI.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace BackendAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ConceptoDePagoController : ControllerBase
    {
        private readonly AppDbContext _context;

        public ConceptoDePagoController(AppDbContext context)
        {
            _context = context;
        }

        // Obtener todos los conceptos de pago
        [HttpGet]
        public async Task<ActionResult<IEnumerable<ConceptoDePago>>> GetConceptos()
        {
            return await _context.ConceptoDePagos.ToListAsync();
        }

        // Obtener un concepto de pago por ID
        [HttpGet("{id}")]
        public async Task<ActionResult<ConceptoDePago>> GetConcepto(int id)
        {
            var concepto = await _context.ConceptoDePagos.FindAsync(id);
            if (concepto == null) return NotFound();
            return concepto;
        }

        // Crear un nuevo concepto de pago
        [HttpPost]
        public async Task<ActionResult<ConceptoDePago>> PostConcepto(ConceptoDePago concepto)
        {
            _context.ConceptoDePagos.Add(concepto);
            await _context.SaveChangesAsync();
            return CreatedAtAction(nameof(GetConcepto), new { id = concepto.Identificador }, concepto);
        }

        // Editar un concepto de pago
        [HttpPut("{id}")]
        public async Task<IActionResult> PutConcepto(int id, ConceptoDePago concepto)
        {
            if (id != concepto.Identificador) return BadRequest();

            _context.Entry(concepto).State = EntityState.Modified;
            await _context.SaveChangesAsync();
            return NoContent();
        }

        // Eliminar un concepto de pago
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteConcepto(int id)
        {
            var concepto = await _context.ConceptoDePagos.FindAsync(id);
            if (concepto == null) return NotFound();

            _context.ConceptoDePagos.Remove(concepto);
            await _context.SaveChangesAsync();
            return NoContent();
        }
    }
}
