<?php
// public/products.php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';
require_once __DIR__ . '/../includes/functions.php';

// Asegurarse de que el usuario esté autenticado
if (!isAuthenticated()) {
    redirect('login.php');
}

// Lógica para Añadir/Actualizar/Eliminar Productos
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add' || $action === 'update') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);

            if (empty($name) || $price <= 0 || $stock < 0) {
                $message = '<div class="message error">Nombre, precio y stock son obligatorios y deben ser válidos.</div>';
            } else {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$name, $description, $price, $stock])) {
                        $message = '<div class="message success">Producto añadido exitosamente.</div>';
                    } else {
                        $message = '<div class="message error">Error al añadir el producto.</div>';
                    }
                } elseif ($action === 'update') {
                    $product_id = intval($_POST['product_id'] ?? 0);
                    if ($product_id > 0) {
                        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
                        if ($stmt->execute([$name, $description, $price, $stock, $product_id])) {
                            $message = '<div class="message success">Producto actualizado exitosamente.</div>';
                        } else {
                            $message = '<div class="message error">Error al actualizar el producto.</div>';
                        }
                    } else {
                        $message = '<div class="message error">ID de producto inválido para actualizar.</div>';
                    }
                }
            }
        } elseif ($action === 'delete') {
            $product_id = intval($_POST['product_id'] ?? 0);
            if ($product_id > 0) {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                if ($stmt->execute([$product_id])) {
                    $message = '<div class="message success">Producto eliminado exitosamente.</div>';
                } else {
                    $message = '<div class="message error">Error al eliminar el producto.</div>';
                }
            } else {
                $message = '<div class="message error">ID de producto inválido para eliminar.</div>';
            }
        }
    }
}

// Obtener todos los productos para mostrar
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
    $products = $stmt->fetchAll();
} catch (\PDOException $e) {
    $message = '<div class="message error">Error al cargar los productos: ' . $e->getMessage() . '</div>';
}

$pageTitle = "Gestión de Productos - Maniboys";
require_once __DIR__ . '/../includes/header.php';
?>

        <h1>Gestión de Productos</h1>
        <?php echo $message; ?>

        <h2><?php echo isset($_GET['edit']) ? 'Editar Producto' : 'Añadir Nuevo Producto'; ?></h2>
        <?php
        $editProduct = null;
        if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
            $editId = intval($_GET['edit']);
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$editId]);
            $editProduct = $stmt->fetch();
            if (!$editProduct) {
                $message = '<div class="message error">Producto no encontrado.</div>';
                unset($_GET['edit']); // Limpiar para que no intente editar un producto inexistente
            }
        }
        ?>
        <form action="products.php" method="POST">
            <input type="hidden" name="action" value="<?php echo $editProduct ? 'update' : 'add'; ?>">
            <?php if ($editProduct): ?>
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($editProduct['id']); ?>">
            <?php endif; ?>

            <label for="name">Nombre del Producto:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" required>

            <label for="description">Descripción:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>

            <label for="price">Precio:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($editProduct['price'] ?? ''); ?>" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($editProduct['stock'] ?? ''); ?>" required>

            <button type="submit"><?php echo $editProduct ? 'Actualizar Producto' : 'Añadir Producto'; ?></button>
            <?php if ($editProduct): ?>
                <a href="products.php" style="text-align: center; display: block; margin-top: 10px; color: #dc3545;">Cancelar Edición</a>
            <?php endif; ?>
        </form>

        <h2>Listado de Productos</h2>
        <?php if (empty($products)): ?>
            <p>No hay productos registrados aún.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($product['stock']); ?></td>
                            <td class="action-buttons">
                                <a href="products.php?edit=<?php echo htmlspecialchars($product['id']); ?>"><button class="edit-btn">Editar</button></a>
                                <form action="products.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <button type="submit" class="delete-btn">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>