using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BackendAPI.Models
{
    [Table("asientos_contables")] // <-- cambiar aquí
    public class AsientoContable
    {
        [Key]
        [Column("id")]
        public int Id { get; set; }

        [Required]
        [Column("descripcion")]
        public string Descripcion { get; set; }

        [Required]
        [Column("fecha_asiento")]
        public DateTime FechaAsiento { get; set; }

        [Required]
        [Column("total_monto")]
        public decimal TotalMonto { get; set; }

        [Required]
        [Column("estado")]
        public string Estado { get; set; }

        [Column("external_object_id")]
        public string? ExternalObjectId { get; set; }

        [Column("sistema_auxiliar_id")]
        public int SistemaAuxiliarId { get; set; } = 6;
    }
}
