using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BackendAPI.Models
{
    [Table("detalle_asiento")]
    public class DetalleAsiento
    {
        [Key]
        public int Id { get; set; }

        [ForeignKey("AsientoContable")]
        public int AsientoId { get; set; }

        public int CuentaId { get; set; }

        public string TipoMovimiento { get; set; } // DB o CR

        public decimal Monto { get; set; }

        public AsientoContable AsientoContable { get; set; }
    }
}
