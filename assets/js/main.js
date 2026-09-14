document.addEventListener('DOMContentLoaded', function () {
  let allProjects = []
  let itemsPerPage = 3
  let currentIndex = 0

  const grid = document.getElementById('projetos-grid')
  const loadMoreBtn = document.getElementById('load-more-btn')

  fetch('projetos.json')
    .then((response) => response.json())
    .then((data) => {
      allProjects = data
      renderProjects()
    })
    .catch((error) => console.error('Erro ao carregar os projetos:', error))

  function renderProjects() {
    const currentLang = window.i18n
      ? window.i18n.lang
      : document.body.getAttribute('data-lang') || 'pt'
    const nextIndex = currentIndex + itemsPerPage
    const slice = allProjects.slice(currentIndex, nextIndex)

    slice.forEach((project) => {
      const article = document.createElement('article')

      const img = document.createElement('img')
      img.src = project.imagem
      img.alt = window.i18n
        ? `${window.i18n.previewProject} ${project[`titulo_${currentLang}`]}`
        : currentLang === 'pt'
          ? `Prévia do projeto ${project.titulo_pt}`
          : `Preview of ${project.titulo_en}`
      img.loading = 'lazy'
      img.decoding = 'async'

      const content = document.createElement('div')
      content.className = 'content'

      const catDiv = document.createElement('div')
      const catSpan = document.createElement('span')
      catSpan.textContent = project[`categoria_${currentLang}`]
      catDiv.appendChild(catSpan)
      content.appendChild(catDiv)

      const titleDiv = document.createElement('div')
      const h3 = document.createElement('h3')
      h3.textContent = project[`titulo_${currentLang}`]
      titleDiv.appendChild(h3)
      content.appendChild(titleDiv)

      const descDiv = document.createElement('div')
      const atuacaoP = document.createElement('p')
      atuacaoP.className = 'project-atuacao'

      const strongTag = document.createElement('strong')
      strongTag.textContent = currentLang === 'pt' ? 'Atuação: ' : 'Role: '

      const atuacaoText = document.createTextNode(
        project[`atuacao_${currentLang}`],
      )

      atuacaoP.appendChild(strongTag)
      atuacaoP.appendChild(atuacaoText)

      const p = document.createElement('p')
      p.textContent = project[`descricao_${currentLang}`]

      descDiv.appendChild(atuacaoP)
      descDiv.appendChild(p)
      content.appendChild(descDiv)

      let techUl = null
      if (project.tech && project.tech.length) {
        techUl = document.createElement('ul')
        techUl.className = 'tech-list'
        project.tech.forEach((tech) => {
          const li = document.createElement('li')
          li.textContent = tech
          techUl.appendChild(li)
        })
        content.appendChild(techUl)
      }

      const a = document.createElement('a')
      a.href = project.link
      a.target = '_blank'
      a.rel = 'noopener noreferrer'
      a.textContent = window.i18n
        ? window.i18n.viewProject
        : currentLang === 'pt'
          ? 'Ver projeto'
          : 'View project'
      content.appendChild(a)

      article.appendChild(img)
      article.appendChild(content)
      grid.appendChild(article)
    })

    currentIndex = nextIndex
    if (currentIndex >= allProjects.length) {
      loadMoreBtn.hidden = true
    } else {
      loadMoreBtn.hidden = false
    }
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', () => {
      const originalText = window.i18n
        ? window.i18n.loadMore
        : 'Ver mais projetos'

      loadMoreBtn.textContent = window.i18n
        ? window.i18n.loading
        : 'Carregando...'

      loadMoreBtn.disabled = true
      loadMoreBtn.setAttribute('aria-busy', 'true')

      setTimeout(() => {
        renderProjects()

        if (currentIndex < allProjects.length) {
          loadMoreBtn.textContent = originalText
          loadMoreBtn.disabled = false
          loadMoreBtn.removeAttribute('aria-busy')
        }
      }, 500)
    })
  }

  const header = document.querySelector('header')

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.classList.add('scrolled')
    } else {
      header.classList.remove('scrolled')
    }
  })

  const form = document.querySelector('form')
  const modal = document.querySelector('#modal-sucesso')
  const fecharModal = document.querySelector('#fechar-modal')
  const submitBtn = form ? form.querySelector('button[type="submit"]') : null

  function abrirModal() {
    modal.classList.add('ativo')
    fecharModal.focus()
  }

  function fecharModalFunc() {
    modal.classList.remove('ativo')
    if (submitBtn) submitBtn.focus()
  }

  if (fecharModal) {
    fecharModal.addEventListener('click', fecharModalFunc)
  }

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        fecharModalFunc()
      }
    })
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('ativo')) {
      fecharModalFunc()
    }
  })

  function showError(inputElement, message) {
    const wrapper =
      inputElement.closest('.custom-select-wrapper') ||
      inputElement.parentElement

    inputElement.classList.add('error')
    if (wrapper.classList.contains('custom-select-wrapper')) {
      wrapper.classList.add('error')
    }

    let errorSpan = wrapper.querySelector('.error-message')
    if (!errorSpan) {
      errorSpan = document.createElement('span')
      errorSpan.className = 'error-message'
      wrapper.appendChild(errorSpan)
    }
    errorSpan.textContent = message
  }

  function clearError(inputElement) {
    const wrapper =
      inputElement.closest('.custom-select-wrapper') ||
      inputElement.parentElement

    inputElement.classList.remove('error')
    if (wrapper.classList.contains('custom-select-wrapper')) {
      wrapper.classList.remove('error')
    }

    const errorSpan = wrapper.querySelector('.error-message')
    if (errorSpan) {
      errorSpan.remove()
    }
  }

  const nome = document.querySelector('#nome')
  const email = document.querySelector('#email')
  const tipoProjeto = document.querySelector('#tipo_projeto')
  const prazo = document.querySelector('#prazo_desejado')
  const layout = document.querySelector('#possui_layout')
  const contrato = document.querySelector('#tipo_contrato')
  const mensagem = document.querySelector('#mensagem')

  if (nome) {
    nome.addEventListener('input', () => {
      if (nome.value.trim()) clearError(nome)
    })
  }

  if (email) {
    email.addEventListener('input', () => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (emailRegex.test(email.value.trim())) clearError(email)
    })
  }

  if (mensagem) {
    mensagem.addEventListener('input', () => {
      if (mensagem.value.trim()) clearError(mensagem)
    })
  }

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault()
      let isValid = true

      if (!nome.value.trim()) {
        showError(nome, window.i18n.formNameRequired)
        isValid = false
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!email.value.trim()) {
        showError(email, window.i18n.formEmailRequired)
        isValid = false
      } else if (!emailRegex.test(email.value.trim())) {
        showError(email, window.i18n.formEmailInvalid)
        isValid = false
      }

      if (!tipoProjeto.value) {
        showError(tipoProjeto, window.i18n.formProjectRequired)
        isValid = false
      }

      if (!prazo.value) {
        showError(prazo, window.i18n.formDeadlineRequired)
        isValid = false
      }

      if (!layout.value) {
        showError(layout, window.i18n.formOptionRequired)
        isValid = false
      }

      if (!contrato.value) {
        showError(contrato, window.i18n.formOptionRequired)
        isValid = false
      }

      if (!mensagem.value.trim()) {
        showError(mensagem, window.i18n.formMessageRequired)
        isValid = false
      }

      if (!isValid) return
      const submitBtnForm = form.querySelector('button[type="submit"]')
      const originalText = submitBtnForm ? submitBtnForm.textContent : 'Enviar'
      if (submitBtnForm) {
        submitBtnForm.textContent = window.i18n.formSending
        submitBtnForm.disabled = true
        submitBtnForm.style.opacity = '0.7'
      }

      const formData = new FormData(form)

      try {
        const response = await fetch('envia.php', {
          method: 'POST',
          body: formData,
        })

        const data = await response.json()

        if (response.ok) {
          abrirModal()
          form.reset()
          document
            .querySelectorAll('.custom-select-wrapper')
            .forEach((wrapper) => {
              const span = wrapper.querySelector('.select-trigger span')
              const hiddenInput = wrapper.querySelector('input[type="hidden"]')

              span.textContent = window.i18n.formResetSelect
              span.style.color = '#1f2937'
              hiddenInput.value = ''

              wrapper.querySelectorAll('.custom-option').forEach((option) => {
                option.setAttribute('aria-selected', 'false')
              })
            })
        } else {
          alert(data.error || window.i18n.formSubmitError)
        }
      } catch (error) {
        alert(window.i18n.formConnectionError)
      } finally {
        if (submitBtnForm) {
          submitBtnForm.textContent = originalText
          submitBtnForm.disabled = false
          submitBtnForm.style.opacity = '1'
        }
      }
    })
  }

  const selectWrappers = document.querySelectorAll('.custom-select-wrapper')

  function closeAllSelects(except = null) {
    selectWrappers.forEach((wrapper) => {
      if (wrapper !== except) {
        wrapper.classList.remove('open')

        const trigger = wrapper.querySelector('.select-trigger')
        if (trigger) {
          trigger.setAttribute('aria-expanded', 'false')
        }
      }
    })
  }

  selectWrappers.forEach((wrapper) => {
    const trigger = wrapper.querySelector('.select-trigger')
    const options = Array.from(wrapper.querySelectorAll('.custom-option'))
    const hiddenInput = wrapper.querySelector('input[type="hidden"]')
    const triggerText = trigger.querySelector('span')

    function closeSelect(returnFocus = false) {
      wrapper.classList.remove('open')
      trigger.setAttribute('aria-expanded', 'false')

      if (returnFocus) {
        trigger.focus()
      }
    }

    function selectOption(option) {
      triggerText.textContent = option.textContent.trim()
      triggerText.style.color = '#1f2937'
      hiddenInput.value = option.dataset.value

      options.forEach((item) => {
        item.setAttribute('aria-selected', 'false')
      })

      option.setAttribute('aria-selected', 'true')
      clearError(hiddenInput)
      closeSelect(true)
    }

    function openSelect(initialIndex = 0) {
      closeAllSelects(wrapper)
      wrapper.classList.add('open')
      trigger.setAttribute('aria-expanded', 'true')

      const selectedIndex = options.findIndex(
        (option) => option.dataset.value === hiddenInput.value,
      )

      const optionToFocus =
        selectedIndex >= 0 ? options[selectedIndex] : options[initialIndex]

      optionToFocus?.focus()
    }

    trigger.addEventListener('click', () => {
      if (wrapper.classList.contains('open')) {
        closeSelect()
      } else {
        openSelect()
      }
    })

    trigger.addEventListener('keydown', (event) => {
      if (['Enter', ' ', 'ArrowDown', 'ArrowUp'].includes(event.key)) {
        event.preventDefault()
        openSelect(event.key === 'ArrowUp' ? options.length - 1 : 0)
      }

      if (event.key === 'Escape') {
        closeSelect()
      }
    })

    options.forEach((option, index) => {
      option.addEventListener('click', () => {
        selectOption(option)
      })

      option.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault()
          selectOption(option)
        }

        if (event.key === 'ArrowDown') {
          event.preventDefault()
          options[(index + 1) % options.length].focus()
        }

        if (event.key === 'ArrowUp') {
          event.preventDefault()
          options[(index - 1 + options.length) % options.length].focus()
        }

        if (event.key === 'Escape') {
          event.preventDefault()
          closeSelect(true)
        }

        if (event.key === 'Tab') {
          closeSelect()
        }
      })
    })
  })

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.custom-select-wrapper')) {
      closeAllSelects()
    }
  })

  const menuToggle = document.querySelector('.menu-toggle')
  const nav = document.querySelector('header nav')
  const navContent = document.querySelector('.nav-content')
  const menuOverlay = document.querySelector('.menu-overlay')

  function toggleMenu() {
    const isOpen = nav.classList.toggle('active')
    if (navContent) navContent.classList.toggle('active')
    if (menuOverlay) menuOverlay.classList.toggle('active')

    menuToggle.setAttribute('aria-expanded', isOpen)
    if (window.i18n) {
      menuToggle.setAttribute(
        'aria-label',
        isOpen ? window.i18n.ariaFecharMenu : window.i18n.ariaAbrirMenu,
      )
    }

    const icon = menuToggle.querySelector('i')
    if (icon) {
      icon.className = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'
    }
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', toggleMenu)
  }

  if (menuOverlay) {
    menuOverlay.addEventListener('click', toggleMenu)
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav && nav.classList.contains('active')) {
      toggleMenu()
      menuToggle.focus()
    }
  })

  document.querySelectorAll('header nav a').forEach((link) => {
    link.addEventListener('click', () => {
      if (nav && nav.classList.contains('active')) {
        toggleMenu()
      }
    })
  })
})
