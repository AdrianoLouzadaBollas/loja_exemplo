CREATE TABLE `cores` (
  `corId` int(11) NOT NULL,
  `corDescricao` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `produtos` (
  `produtoId` int(11) NOT NULL,
  `produtoNome` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `produtoImagem` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `produtoTamanho` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `produtoMedida` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `produtoCor` int(11) NOT NULL,
  `produtoGenero` int(11) NOT NULL,
  `produtoMarca` int(11) NOT NULL,
  `produtoModelo` int(11) NOT NULL,
  `produtoDetalhes` text COLLATE utf8mb4_general_ci,
  `produtoPrecoCusto` decimal(10,2) NOT NULL,
  `produtoPrecoVenda` decimal(10,2) NOT NULL,
  `produtoQtdAtual` int(11) NOT NULL,
  `produtoQtdMinima` int(11) NOT NULL,
  `produtoQtdMaxima` int(11) NOT NULL,
  `produtoDataCadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
