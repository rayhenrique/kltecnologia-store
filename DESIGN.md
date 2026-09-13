---
name: KL Tecnologia
description: Sistema visual da vitrine e do painel do e-commerce de produtos digitais.
status: active
canonical_runtime: resources/css/app.css
colors:
  primary: "#2563EB"
  ink: "#102A43"
  ink-soft: "#486581"
  brand-strong: "#1D4ED8"
  accent: "#0F766E"
  canvas: "#F4F7FB"
  surface: "#FFFFFF"
  border: "#D7E0EA"
  success: "#157347"
  warning: "#B45309"
  danger: "#B42318"
typography:
  display:
    fontFamily: "Sora, ui-sans-serif, sans-serif"
    fontWeight: 700
  body:
    fontFamily: "DM Sans, ui-sans-serif, sans-serif"
    fontWeight: 400
  utility:
    fontFamily: "IBM Plex Mono, ui-monospace, monospace"
    fontWeight: 500
spacing:
  page-x-sm: "1rem"
  page-x: "2rem"
  section-y-sm: "3rem"
  section-y: "6rem"
rounded:
  control: "0.75rem"
  card: "1rem"
  panel: "1.25rem"
components:
  page:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
  surface:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.ink}"
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.surface}"
    rounded: "{rounded.control}"
  button-primary-hover:
    backgroundColor: "{colors.brand-strong}"
  helper-text:
    textColor: "{colors.ink-soft}"
  product-cut:
    backgroundColor: "{colors.accent}"
  divider:
    backgroundColor: "{colors.border}"
  status-success:
    textColor: "{colors.success}"
  status-warning:
    textColor: "{colors.warning}"
  button-danger:
    backgroundColor: "{colors.danger}"
    textColor: "{colors.surface}"
---

# KL Tecnologia Design System

## Direção visual

A interface combina a clareza de um catálogo técnico com a confiança de um painel SaaS. Fundo azul muito claro, superfícies brancas, texto azul-marinho e azul vivo formam a base. Verde-petróleo sinaliza entrega e sucesso. A assinatura visual é um pequeno recorte diagonal nos cartões de produto, inspirado em abas de arquivo digital.

O conteúdo é direto e brasileiro: preços em reais, datas em `pt-BR`, linguagem simples e chamadas que descrevem a ação. A vitrine pode ser mais expressiva; as áreas autenticadas priorizam densidade controlada, leitura e previsibilidade.

## Tipografia

- `Sora` em títulos e números de destaque.
- `DM Sans` em navegação, texto e formulários.
- `IBM Plex Mono` em identificadores de pedidos, etiquetas e metadados curtos.

## Regras de composição

- Conteúdo central com largura máxima de 80rem e respiro lateral definido por `--space-page-x`.
- Cartões usam borda visível e sombra curta; elevação maior só em modais.
- Botões primários usam azul; ações destrutivas usam vermelho e sempre abrem confirmação.
- Foco é sempre visível. Mensagens de erro ficam ligadas ao respectivo campo.
- O arquivo binário do produto nunca aparece como URL na interface.

## Fonte canônica

Os tokens executáveis em `resources/css/app.css` são a fonte canônica de runtime. Este documento registra a intenção e deve ser atualizado junto com qualquer mudança de token ou comportamento visual.
