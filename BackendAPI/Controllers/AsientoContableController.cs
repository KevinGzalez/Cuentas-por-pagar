using BackendAPI.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Collections.Generic;
using System.Threading.Tasks;
using System.Linq;

namespace BackendAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AsientosContablesController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AsientosContablesController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/AsientosContables
        [HttpGet]
        public async Task<ActionResult<IEnumerable<AsientoContable>>> GetAsientos()
        {
            return await _context.AsientosContables.OrderByDescending(a => a.FechaAsiento).ToListAsync();
        }

        // GET: api/AsientosContables/{id}
        [HttpGet("{id}")]
        public async Task<ActionResult<AsientoContable>> GetAsiento(int id)
        {
            var asiento = await _context.AsientosContables.FindAsync(id);
            if (asiento == null) return NotFound();
            return asiento;
        }

        // POST: api/AsientosContables
  [HttpPost]
public async Task<ActionResult<AsientoContable>> PostAsiento(AsientoContable asiento)
{
    _context.AsientosContables.Add(asiento);
    await _context.SaveChangesAsync();
    return CreatedAtAction("GetAsiento", new { id = asiento.Id }, asiento);
}

        // PUT: api/AsientosContables/{id} (para marcarlo como contabilizado)
        [HttpPut("{id}")]
        public async Task<IActionResult> PutAsiento(int id, AsientoContable asiento)
        {
            if (id != asiento.Id) return BadRequest();

            _context.Entry(asiento).State = EntityState.Modified;
            await _context.SaveChangesAsync();
            return NoContent();
        }
    }
}
