import { createRoot } from 'react-dom/client';
import TextType from './TextType';

document.querySelectorAll('.react-ui').forEach(el => {
  const component = el.getAttribute('data-component');
  if (component === 'text-type') {
    const texts = (el.getAttribute('data-texts') || '').split('|');
    createRoot(el).render(
      <TextType
        texts={texts}
        typingSpeed={75}
        pauseDuration={1500}
        deletingSpeed={50}
        showCursor={true}
        cursorCharacter="_"
      />
    );
  }
});