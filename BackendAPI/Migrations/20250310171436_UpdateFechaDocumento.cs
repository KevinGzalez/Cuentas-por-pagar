using System;
using Microsoft.EntityFrameworkCore.Metadata;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace BackendAPI.Migrations
{
    /// <inheritdoc />
    public partial class UpdateFechaDocumento : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.RenameColumn(
                name: "TipoDePersona",
                table: "Proveedores",
                newName: "tipo_de_persona");

            migrationBuilder.CreateTable(
                name: "entrada_de_documentos",
                columns: table => new
                {
                    numero_documento = table.Column<int>(type: "int", nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    numero_factura_a_pagar = table.Column<int>(type: "int", nullable: false),
                    fecha_document = table.Column<DateTime>(type: "datetime(6)", nullable: false),
                    Monto = table.Column<decimal>(type: "decimal(18,2)", nullable: false),
                    fecha_registro = table.Column<DateTime>(type: "datetime(6)", nullable: false),
                    proveedor_id = table.Column<int>(type: "int", nullable: false),
                    estado = table.Column<string>(type: "varchar(50)", maxLength: 50, nullable: false)
                        .Annotation("MySql:CharSet", "utf8mb4")
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_entrada_de_documentos", x => x.numero_documento);
                })
                .Annotation("MySql:CharSet", "utf8mb4");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "entrada_de_documentos");

            migrationBuilder.RenameColumn(
                name: "tipo_de_persona",
                table: "Proveedores",
                newName: "TipoDePersona");
        }
    }
}
