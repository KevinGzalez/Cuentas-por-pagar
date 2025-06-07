using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;


//Dejar como está, cuando actualicen, dejen la información que está para que no se desconfigure la api.
namespace BackendAPI.Models
{
    public class AppDbContext : DbContext
    {
        private readonly IConfiguration _configuration;

        public AppDbContext(IConfiguration configuration)
        {
            _configuration = configuration;
        }

        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            if (!optionsBuilder.IsConfigured)
            {
                var connectionString = _configuration.GetConnectionString("DefaultConnection");
                optionsBuilder.UseMySql(connectionString, ServerVersion.AutoDetect(connectionString));
            }
        }

        public DbSet<ConceptoDePago> ConceptoDePagos { get; set; }
        public DbSet<Proveedor> Proveedores { get; set; }
        public DbSet<EntradaDeDocumentos> EntradaDeDocumentos { get; set; }
        public DbSet<AsientoContable> AsientosContables { get; set; }


        public DbSet<DetalleAsiento> DetallesAsientos { get; set; }


    }
}